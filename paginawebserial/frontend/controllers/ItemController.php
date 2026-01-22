<?php

namespace frontend\controllers;

use backend\models\DocumentHeader;
use backend\models\DocumentPosition;
use common\models\ConfigLabel;
use common\models\Item;
use common\models\SerialRules;
use common\models\SerialType;
use common\models\User;
use console\models\Wms;
use frontend\models\RuleAssignment;
use frontend\models\StoreAssignment;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
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
   * Finds the Item model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Item the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = Item::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }


  /**
   * Lists all Item models.
   * @return mixed
   */
  public function actionIndex()
  {
    $query = new Item();
    $dataProvider = $query->search(Yii::$app->request->queryParams);
    return $this->render('index', [
      'dataProvider' => $dataProvider,
      'searchModel' => $query,
    ]);
  }

  /**
   * Displays a single Item model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {
    $modelRule =  SerialRules::findOne(['item_id' => $id]);
    $serialTypes = SerialType::find()->all();
    $model =  $this->findModel($id);

    if (!$modelRule) {
      $modelRule = new SerialRules();
      $modelRule->item_id = $id;
      $modelRule->dateto = date('Y/m/d');
      $modelRule->datefrom = date('2050/12/31');
      $modelRule->serialchk = 1;
      $modelRule->customer_id = $model->customer->id;
      // $modelRule->usernr = Yii::$app->user->identity->id;
    }else {
      $modelRule->item_id = $id;
      $modelRule->dateto = date('Y/m/d');
      $modelRule->datefrom = date('2050/12/31');
      $modelRule->serialchk = 1;
    }
    // if (isset($_POST["label_types"])) {
    //   $label_types = $_POST["label_types"];
    //   $modelRule->config_id = $label_types;
    // } else {
    //   $label_types = null;
    //   $modelRule->config_id = $label_types;
    // }

    // if (isset($_POST['optionType'])) {
    //   $optionType = $_POST["optionType"];
    //   $modelRule->reference_id = $optionType;
    // } else {
    //   $optionType = null;
    //   $modelRule->reference_id = $optionType;
    // }

    // $item_id= SerialRules::find()->where(['item_id'=>$id])->one();
    // $config= ConfigLabel::find()->where(['serial_rule_id'=>$item_id->id]);


    $sql = "SELECT cl.*  from config_label cl 
    inner join serial_rules sr 
    on sr.config_label_id = cl.id 
    WHERE item_id=" . $id;


    $dataProvider  = new SqlDataProvider([
      'sql' => $sql,
    ]);



    if ($modelRule->load(Yii::$app->getRequest()->post())) {
      if ($modelRule->save()) {
        Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
          [
            'title' => 'Se ha guardado correctamente',
            'timer' => 2000,
            'confirmButtonText' => 'Continuar!',
          ]
        ]);
      } else {
        Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
          [
            'title' => 'Ha ocurrido' . implode([$modelRule->id]),
            'timer' => 2000,
            'confirmButtonText' => 'Continuar!',
          ]
        ]);
      }
    }


    

    return $this->render('view', [
      'model' => $this->findModel($id),
      'modelRule' => $modelRule,
      'serialTypes' => $serialTypes,
      'dataProvider' => $dataProvider,
    ]);
  }


  public function actionViewStores($id)
  {
    $model = $this->findModel($id);
    if ($model) {
      $model = new RuleAssignment($id, $model, 'name', ConfigLabel::class);
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
    $model = new RuleAssignment($id, $model, 'name', ConfigLabel::class);
    //$model->target = Store::class;
    $success = $model->revoke($items);
    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }

  public function actionAssign($id)
  {
    $model = $this->findModel($id);
    $items = Yii::$app->getRequest()->post('items', []);
    $model = new RuleAssignment($id, $model, 'name', ConfigLabel::class);
    //  $model->target = Store::class;
    $success = $model->assign($items);

    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }






  /**
   * Creates a new Item model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new Item();

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
   * Updates an existing Item model.
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
   * Deletes an existing Item model.
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



  public function actionImport()
  {
    $wms = new Wms();
    $wms->getSerialized();
    Yii::$app->session->setFlash('success', 'Importación finalizada');
    $this->redirect(['index']);
  }

  public function actionChangeStatus($id, $value)
  {
    $item = Item::findOne(['id' => $id]);
    $documentPosition = DocumentPosition::find()->where(['item_id' => $id])->one();
    $documentHeader = DocumentHeader::find()->where(['id' => $documentPosition->document_id])->one();
    switch ($value) {
      case 10:
        $item->status = 10;
        $item->save(false);
        $documentHeader->status = 12;
        $documentHeader->save(false);
        if ($item->save(false)) {
          $this->GenerateMessage($item->name, $item->status);
        }
        break;

      case 11:
        $item->status = 11;
        $item->save(false);
        $documentHeader->status = 16;
        $documentHeader->save(false);
        if ($item->save(false)) {
          $this->GenerateMessage($item->name, $item->status);
        }
        break;
    }



    $this->redirect([$id]);
  }
  public function GenerateMessage($itemName, $status)
  {
    Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_INFO, [
      [
        'title' => $status == 10 ? 'Material Activado' : 'Material Desactivado',
        'text' => $itemName,
        'timer' => 2000,
        'confirmButtonColor' => '#3085d6',
      ],

    ]);
  }
  public function actionSave()
  {
    echo "test";
    die();
  }
}
