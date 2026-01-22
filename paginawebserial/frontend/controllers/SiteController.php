<?php

namespace frontend\controllers;

use common\models\conference\Video;
use common\models\medical\Staff;
use common\models\Patient;
use common\models\bus\Llamadas;
use common\models\conference\base\Account;
use common\models\medical\Appointment;
use common\models\User;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Redis;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
  /**
   * Logs out the current user.
   *
   * @return mixed
   */
  public function actionLogout()
  {
    $user = Yii::$app->user->id;
    /*if ($user) {
            $model = User::findOne($user);
            $model->endSession();
        }*/
    Yii::$app->user->logout();

    return $this->goHome();
  }

  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['logout', 'signup'],
        'rules' => [
          [
            'actions' => ['signup'],
            'allow' => true,
            'roles' => ['?'],
          ],
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'logout' => ['get'],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function actions()
  {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
      ],
    ];
  }

  /**
   * Displays homepage.
   *
   * @return mixed
   */
  public function actionIndex($init = '2019-02-01')
  {
    $end = $init;
    if (isset($_POST['data'])) {
      $init = $_POST['data'];
      $end = $_POST['data_end'];
    }

    if (Yii::$app->authManager->getAssignment('sysadmin', Yii::$app->user->getId())) {
      return $this->renderSysAdmin();
    } else if (Yii::$app->authManager->getAssignment('Client', Yii::$app->user->getId())) {
      return $this->renderClient();
    } else {
      return $this->render('/site/about');
    }
  }

  /**
   * Logs in a user.
   *
   * @return mixed
   */
  public function actionLogin($id = null)
  {
    if (!Yii::$app->user->isGuest) {
      return $this->goHome();
    }

    $model = new LoginForm();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {

      return $this->goBack();
    } else {
      $model->password = '';

      return $this->render('login', [
        'id' => $id,
        'model' => $model,
      ]);
    }
  }

  /**
   * Displays about page.
   *
   * @return mixed
   */
  public function actionAbout()
  {
    return $this->render('about');
  }

  /**
   * Signs user up.
   *
   * @return mixed
   */
  public function actionSignup()
  {
    $model = new SignupForm();
    if ($model->load(Yii::$app->request->post())) {
      if ($user = $model->signup()) {
        if (Yii::$app->getUser()->login($user)) {
          return $this->goHome();
        }
      }
    }

    return $this->render('signup', [
      'model' => $model,
    ]);
  }

  /**
   * Requests password reset.
   *
   * @return mixed
   */
  public function actionRequestPasswordReset()
  {
    $model = new PasswordResetRequestForm();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($model->sendEmail()) {
        Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

        return $this->goHome();
      } else {
        Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
      }
    }

    return $this->render('requestPasswordResetToken', [
      'model' => $model,
    ]);
  }

  /**
   * Resets password.
   *
   * @param string $token
   * @return mixed
   * @throws BadRequestHttpException
   */
  public function actionResetPassword($token)
  {
    try {
      $model = new ResetPasswordForm($token);
    } catch (InvalidParamException $e) {
      throw new BadRequestHttpException($e->getMessage());
    }

    if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
      Yii::$app->session->setFlash('success', 'New password saved.');

      return $this->goHome();
    }

    return $this->render('resetPassword', [
      'model' => $model,
    ]);
  }

  private function renderSysAdmin()
  {
    $model= User::findOne(['id'=>Yii::$app->user->getId()]);
    $dataProvider = new ActiveDataProvider([
      'query' => User::find()->where(['id' => Yii::$app->user->getId()]),
      'pagination' => [
        'pageSize' => 10,
      ],
    ]);

    return $this->render('sysadmin', [
      'model' => $model,
      'dataProvider' => $dataProvider,
    ]);
    // return $this->render('sysadmin', []);
  }

  private function renderClient()
  {
    return $this->render('client', []);
  }

  public function actionViewChart()
  {
    return $this->render('viewPoints');
  }

  public function actionGetpoints($id)
  {

    $origin = ['base', 'hammer', 'motor', 'drill', 'drilldrill', 'test'];
    $originSelect = $origin[$id - 1];
    $response = [];

    $axis = [
      'motor' =>  [73, 72, 71],
      'hammer' => [74, 76, 75],
      'drill' => [77, 78, 79],
      'drilldrill' => [80, 81, 82],
      'base' => [83, 84, 85],
      'test' => [86, 87, 88],

    ];

    $query = (new Query())
      ->select('id, name')
      ->from('user')
      ->limit(10);

    $sensores = ['AC233F6E2209', 'AC233F6E2201', 'AC233F6E21FE', 'AC233F6E21FC', 'AC233F6E2204', 'AC233F6E2205', 'AC233F6E2206', 'AC233F6E2207'];
    $devices = (new Query)->select(['id'])->from('device')->where(['label' => $sensores]);
    $distance = (new Query)->select('long_v')->from('attribute_kv')->where('attribute_kv.entity_id = ts_kv_latest.entity_id and attribute_key =\'distance\'');

    foreach ($axis[$originSelect] as $axi) {
      $query = (new Query)->select(['y' => 'dbl_v', 'x' => $distance])->from('ts_kv_latest')
        ->where(['entity_id' => $devices])
        ->andWhere(['key' => $axi])
        ->orderBy('x');
      $command = $query->createCommand(Yii::$app->dbp);
      $rows = $command->queryAll();
      $response['data'][] = $rows;
    }

    foreach ($axis['test'] as $axi) {
      $query = (new Query)->select(['y' => 'dbl_v', 'x' => $distance])->from('ts_kv_latest')
        ->where(['entity_id' => $devices])
        ->andWhere(['key' => $axi]);
      $command = $query->createCommand(Yii::$app->dbp);
      $rows = $command->queryAll();
      $response['origin'][] = $rows;
    }

    for ($i = 0; $i < 3; $i++) {
      $pivotx = 0;
      for ($y = 0; $y < count($response['data'][$i]); $y++) {
        $point = $response['origin'][$i][$y];
        $pivot = $response['data'][$i][$y];

        $response['clean'][$i][$y] = ['x' =>  $pivot['x'], 'y' => abs($pivot['y'] - $point['y'])];
      }
    }

    return Json::encode(['message' => $response], JSON_NUMERIC_CHECK);
  }

  public function actionScripts()
  {
    $this->redirect('/app/scripts/index');
  }
}
