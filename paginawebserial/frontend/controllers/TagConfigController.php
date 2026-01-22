<?php

namespace frontend\controllers;

use common\models\ConfigLabel;
use common\models\Item;
use common\models\SerialRules;
use common\models\SerialType;
use common\models\WorkOrder;
use frontend\models\StoreAssignment;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WorkOrderController implements the CRUD actions for WorkOrder model.
 */
class TagConfigController extends Controller
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
   * Lists all WorkOrder models.
   * @return mixed
   */
  public function actionIndex()
  {
    $dataProvider = new ActiveDataProvider([
      'query' => ConfigLabel::find(),
      'pagination' => [
        'pageSize' => 10
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
  protected function findModel($id)
  {
    if (($model = Item::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }
  /**
   * Displays a single WorkOrder model.
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
   * Creates a new WorkOrder model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new WorkOrder();

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
   * Updates an existing WorkOrder model.
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

  public function actionConfig()
  {
    $model = new ConfigLabel();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      // valid data received in $model
      // do something meaningful here about $model ...

      
      if (isset($_POST["label_types"])) {
        $label_types = $_POST["label_types"];
        $model->config_id = $label_types;
      } else {
        $label_types = null;
        $model->config_id = $label_types;
      }

      if (isset($_POST['optionType'])) {
        $optionType = $_POST["optionType"];
        $model->reference_id = $optionType;
      } else {
        $optionType = null;
        $model->reference_id = $optionType;
      }

      if($model->save()){
        Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
          [
            'title' => 'Se ha guardado correctamente',
            'timer' => 2000,
            'confirmButtonText' => 'Continuar!',
          ]
        ]);
      }

      return $this->render('config_label', [
        'model' => $model,
        'serialTypes' => SerialType::find()->all()
      ]);
      
    } else {
      return $this->render('config_label', [
        'model' => $model,
        'serialTypes' => SerialType::find()->all()
      ]);
    }
  }


  public function actionShow($id)
  {
    $sql = "SELECT c.code AS customer,
    i.code,
    i.name,
    i.netweigth ,
    i.unitnet
FROM serial_rules sr
INNER JOIN item i ON i.id =sr.item_id
INNER JOIN config_label cl ON cl.id = sr.config_label_id
INNER JOIN customer c ON i.customer_id = c.id
WHERE cl.id =" . $id;

    $conection = Yii::$app->getDb();
    $command = $conection->createCommand($sql);

    $dataProvider  = new SqlDataProvider([
      'sql' => $sql,
    ]);


    return $this->render('show_config', [
      'dataProvider' => $dataProvider,
    ]);
    // var_dump("askjdkasjkdjkasj");
    // die();
  }



  /**
   * Deletes an existing WorkOrder model.
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
}
