<?php

namespace frontend\controllers;

use common\models\Customer;
use console\models\Wms;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use vova07\console\ConsoleRunner;
use frontend\models\CustomerAssignment;


/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
  /**
   * @inheritDoc
   */
  public function behaviors()
  {
    return array_merge(
      parent::behaviors(),
      [
        'verbs' => [
          'class' => VerbFilter::className(),
          'actions' => [
            'delete' => ['POST'],
          ],
        ],
      ]
    );
  }

  /**
   * Lists all Customer models.
   * @return mixed
   */
  public function actionIndex()
  {
    $dataProvider = new ActiveDataProvider([
      // MODO DEMO: Mostrar todos los clientes activos (status >= 1)
      'query' => Customer::find()->where(['>=', 'status', 1]),
      'pagination' => [
        'pageSize' => 6
      ],
      'sort' => [
        'defaultOrder' => [
          'id' => SORT_DESC,
        ]
      ],

    ]);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single Customer model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {
    return $this->render('view', [
      'model' => $this->findModel($id),
    ]);
  }

  /**
   * Creates a new Customer model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new Customer();

    if ($this->request->isPost) {
      if ($model->load($this->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
      }
    } elseif ($this->request->isAjax) {
      return $this->renderAjax('_form', [
        'model' => $model
      ]);
    } else {
      $model->loadDefaultValues();
    }

    return $this->render('create', [
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing Customer model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);

    if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('update', [
      'model' => $model,
    ]);
  }

  /**
   * Deletes an existing Customer model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionDelete($id)
  {
    $this->findModel($id)->delete();

    return $this->redirect(['index']);
  }

  /**
   * Finds the Customer model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Customer the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = Customer::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }

  public function actionImport($id)
  {
    $wms = new Wms();
    $wms->getCustomers($db = 'dlx');
    $model = $this->findModel($id);
    if ($model) {
      $model = new CustomerAssignment($id, $model, 'code', Customer::class);
      return $this->renderAjax('assignment-customer/view', [
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
    $model = new CustomerAssignment($id, $model, 'code', Customer::class);
    //$model->target = Store::class;
    $success = $model->revoke($items);
    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }

  public function actionAssign($id)
  {
    $model = $this->findModel($id);
    $items = Yii::$app->getRequest()->post('items', []);
    $model = new CustomerAssignment($id, $model, 'code', Customer::class);
    //  $model->target = Store::class;
    $success = $model->assign($items);

    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }
}
