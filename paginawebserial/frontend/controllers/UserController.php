<?php

namespace frontend\controllers;

use common\models\Store;
use frontend\models\StoreAssignment;
use frontend\models\UserAuthLog;
use common\models\User;
use common\models\UserProfile;
use console\models\Userldap;
use dominus77\sweetalert2\Alert;
use frontend\models\SignupForm;
use mdm\admin\models\Assignment;
use mdm\admin\models\form\ChangePassword;
use mdm\admin\models\form\Signup;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AuthController implements the CRUD actions for User model.
 */
class UserController extends \mdm\admin\controllers\UserController
{
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
    ];
  }

  /**
   * Lists all User models.
   * @return mixed
   */
  public function actionIndex()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => User::find(),
    ]);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single User model.
   * @param integer $id
   * @return mixed
   */
  public function actionView($id)
  {
    $store = Store::find()->all();

    $searchModel = new UserAuthLog();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('view', [
      'model' => $this->findModel($id),
      'dataProvider' => $dataProvider,
      'searchModel' => $searchModel,
      'store' => $store
    ]);
  }

  /**
   * Creates a new User model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {

    $model = new SignupForm();
    $profile = new UserProfile;

    if ($model->load(Yii::$app->getRequest()->post()) && $profile->load(Yii::$app->getRequest()->post())) {
      if ($user = $model->signup()) {
        //$profile = new UserProfile;
        $profile->user_id = $user->id;
        $profile->save();
        return $this->redirect(['update', 'id' => $user->id]);
      }
      // Yii::$app->session->setFlash('error', json_encode($model->getErrors()));

    }

    return $this->render('create', [
      'model' => $model,
      'profile' => $profile
    ]);
  }

  /**
   * Updates an existing User model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   */
  public function actionUpdate($id)
  {
    $userId =  Yii::$app->user->getId();
    if (Yii::$app->authManager->getAssignment('sysadmin', $userId) || ($id == $userId) || Yii::$app->authManager->getAssignment('Admon_Plataforma', $userId)) {

      $model = $this->findModel($id);
      $profile  = UserProfile::findOne(['user_id' => $id]);

      if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
        $profile->thumbnail_file = UploadedFile::getInstance($profile, 'thumbnail');
        $profile->signature_file = UploadedFile::getInstance($profile, 'signature');
        $profile->upload();
        /*
                 * $url = Url::previous();
                return $this->redirect($url);
                Url::remember();
                 */
        Yii::$app->session->setFlash('info', 'User ' . $model->fullName . " updateted successfully.");
      }
      return $this->render('update', [
        'model' => $model,
        'profile' => $profile,
        // 'group_model' => $group_model
      ]);
    } else {
      echo 'No tiene suficientes permisos'; //$this->render('/site/error');
    }
  }

  /**
   * Deletes an existing User model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   */
  public function actionDelete($id)
  {
    //$this->findModel($id)->delete();

    return $this->redirect(['/admin/user/index']);
  }

  /**
   * Finds the User model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return User the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = User::findOne($id)) !== null) {
      return $model;
    } else {
      throw new NotFoundHttpException('The requested page does not exist.');
    }
  }

  /**
   * Activate new user
   * @param integer $id
   * @return type
   * @throws UserException
   * @throws NotFoundHttpException
   */
  public function actionActivate($id)
  {
    /* @var $user User */
    $user = $this->findModel($id);

    if ($user->status == User::STATUS_DELETED) {
      $user->status = User::STATUS_ACTIVE;
      if ($user->save()) {
        return $this->redirect(['/admin/user/index']);
      } else {
        $errors = $user->firstErrors;
        throw new UserException(reset($errors));
      }
    }
    return $this->redirect(['/admin/user/index']);
  }

  /**
   * Activate new user
   * @param integer $id
   * @return type
   * @throws UserException
   * @throws NotFoundHttpException
   */
  public function actionUpdatePassword($id)
  {
    $user = $this->findModel($id);
    $new_pass = Yii::$app->request->post('new_pass');
    $user->setPassword($new_pass);
    $user->generateAuthKey();
    $output = '';
    $message = '';

    if (!$user->save()) {
      $output = 'error al guardar';
      $message = $user->getErrors();
    }

    echo Json::encode(['output' => $output, 'message' => $message]);
    return;
  }

  /**
   * Show profile user
   */
  public function actionProfile()
  {
    $change_password = new ChangePassword();
    $model = Yii::$app->user->identity;
    $model->load(Yii::$app->request->post(), 'User');
    $model->save();
    $new_pass = Yii::$app->request->post('ChangePassword');
    if (isset($new_pass['newPassword'])) {
      $change_password->load(Yii::$app->request->post(), 'ChangePassword');
      $change_password->change();
    }


    return $this->render('profile', [
      'model' => $model,
      'change_password' => $change_password
    ]);
  }

  /**
   * Reset password
   * @return string
   */
  public function actionChangePassword()
  {
    if (Yii::$app->request->isAjax) {
      $model = new ChangePassword();
      if ($model->load(Yii::$app->getRequest()->post()) && $model->change()) {
        return "ok";
      } else {
        return json_encode($model->getErrors());
      }
    }
  }

  /**
   * Lists all User models.
   * @return mixed
   */
  public function actionLog()
  {
    $searchModel = new Userauthlog;
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


    return $this->render('log', [
      'dataProvider' => $dataProvider,
      'searchModel' => $searchModel,
    ]);
  }

  public function actionViewPermission($id)
  {
    $model = $this->findModel($id);
    if ($model) {
      $model = new Assignment($id, $model);
      return $this->renderAjax('assignment/view', [
        'model' => $model,
        'idField' => 'id',
        'usernameField' => 'username',
        'fullnameField' => 'fullName',
      ]);
    }
  }

  public function actionViewStores($id)
  {
    $model = $this->findModel($id);
    if ($model) {
      $model = new StoreAssignment($id, $model, 'name', Store::class);
      return $this->renderAjax('assignment-store/view', [
        'model' => $model,
        'idField' => 'id',
        'usernameField' => 'username',
        'fullnameField' => 'fullName',
      ]);
    }
  }

  public function actionRevoke($id)
  {
    $model = $this->findModel($id);
    $items = Yii::$app->getRequest()->post('items', []);
    $model = new StoreAssignment($id, $model, 'name', Store::class);
    //$model->target = Store::class;
    $success = $model->revoke($items);
    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }

  public function actionAssign($id)
  {
    $model = $this->findModel($id);
    $items = Yii::$app->getRequest()->post('items', []);
    $model = new StoreAssignment($id, $model, 'name', Store::class);
    //  $model->target = Store::class;
    $success = $model->assign($items);
    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }

  public function actionImport()
  {
    $users  = new Userldap();
    $users->getUsersFromLdap();
    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, [
      [
          'title' => 'Usuarios Importados Exitosamente',
          'confirmButtonText' => 'Continuar !',
      ]
   ]);
  $this->redirect(["index"]);
  }
}
