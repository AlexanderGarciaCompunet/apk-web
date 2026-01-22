<?php

namespace frontend\controllers;

use common\models\StoreUser;
use common\models\Store;
use console\models\Wms;
use frontend\models\StoreAssignment;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StoreController implements the CRUD actions for Store model.
 */
class StoreController extends Controller
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
   * Lists all Store models.
   * @return mixed
   */
  public function actionIndex()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => Store::find(),
      'pagination' => [
        'pageSize' => 21
      ],
      'sort' => [
        'defaultOrder' => [
          'id' => SORT_ASC,
        ]
      ],
    ]);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single Store model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {
    $dataProvider = new ActiveDataProvider(['query' => StoreUser::find()->where(['status' => 10, 'store_id' => $id])]);
    return $this->render('view', [
      'model' => $this->findModel($id),
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Creates a new Store model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new Store();

    if ($this->request->isPost) {
      if ($model->load($this->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
      }
    } else {
      $model->loadDefaultValues();
    }

    return $this->render('create', [
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing Store model.
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
   * Deletes an existing Store model.
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
   * Finds the Store model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Store the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = Store::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }

  public function actionViewUsers($id)
  {
    $model = $this->findModel($id);
    if ($model) {
      $model = new StoreAssignment($id, $model);
      return $this->renderAjax('assignment/view', [
        'model' => $model,
        'idField' => 'id',
        'usernameField' => 'username',
        'fullnameField' => 'fullName',
      ]);
    }
  }

  public function actionRevoke($id)
  {
    $items = Yii::$app->getRequest()->post('items', []);
    $model = new StoreAssignment($id);
    $success = $model->revoke($items);
    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }

  public function actionAssign($id)
  {
    $items = Yii::$app->getRequest()->post('items', []);
    $model = new StoreAssignment($id);
    $success = $model->assign($items);
    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }

  public function actionImport()
  {
    $wms = new Wms();
    $wms->getStores();
    Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
      [
        'title' => 'Importación finalizada',
        'timer' => 2000,
        'confirmButtonText' => 'Continuar!',
      ]
    ]);
    $this->redirect(['index']);
  }
}
