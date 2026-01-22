<?php

namespace frontend\controllers;

use backend\models\Item;
use backend\models\SerialMaster;
use common\models\DocumentHeader;
use common\models\DocumentPosition;
use common\models\SerialList;
use console\models\Wms;
use frontend\models\OrderAssignment;
use Yii;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\UploadedFile;
use common\models\LpnMaster;


/**
 * OrderController implements the CRUD actions for DocumentHeader model.
 */
class OrderController extends Controller
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
   * Lists all DocumentHeader models.
   * @return mixed
   */
  public function actionIndex()
  {
    $query = new DocumentHeader();
    $dataProvider = $query->search(Yii::$app->request->queryParams);


    return $this->render('index', [
      'dataProvider' => $dataProvider,
      'searchModel' => $query,
    ]);
  }

  /**
   * Displays a single DocumentHeader model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {

    $dataProvider = new ActiveDataProvider([
      'query' => DocumentPosition::find()->where(['document_id' => $id]),
    ]);
    return $this->render('view', [
      'model' => $this->findModel($id),
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Creates a new DocumentHeader model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new DocumentHeader();

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
   * Updates an existing DocumentHeader model.
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
   * Deletes an existing DocumentHeader model.
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
   * Finds the DocumentHeader model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return DocumentHeader the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = DocumentHeader::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }

  public function actionViewUsers($id)
  {
    $model = $this->findModel($id);
    if ($model) {
      $model = new OrderAssignment($id, $model);
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
    $model = new OrderAssignment($id);
    $success = $model->revoke($items);
    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }

  public function actionAssign($id)
  {
    $items = Yii::$app->getRequest()->post('items', []);
    $model = new OrderAssignment($id);
    $success = $model->assign($items);
    Yii::$app->getResponse()->format = 'json';
    return array_merge($model->getItems(), ['success' => $success]);
  }

  public function actionSetStatus()
  {
    return $this->renderAjax('_status');
  }

  public function actionImport()
  {
    $wms = new Wms();
    $wms->getReceived('dlx');
    Yii::$app->session->setFlash('success', 'Importación finalizada');
    $this->redirect(['index']);
  }


  public function actionShowModalUnlock($id)
  {
    $model = $this->findModel($id);
    if ($model) {
      return $this->renderAjax('order_locks/manual_block', [
        'model' => $model,
        'idField' => 'id',
      ]);
    }
  }


  public function actionUnlockOrderByStatus($id)
  {
    $model = $this->findModel($id);
    if ($model->status == 18) {
      $model->status = 12;
      $model->save();
    } else {
      $model->status = 18;
      $model->save();
    }

    $this->redirect([$id]);
  }
  public function actionImportByTxt($id)
  {
    // echo "documento " . $id . '</br>';
    // echo "item_id " . $item_id . '</br>';

    $dataProvider = new ActiveDataProvider([
      'query' => SerialMaster::find()->where(['document_id' => $id]),
      'pagination'=>[
        'pageSize'=>200
      ]
    ]);

    return $this->render('/order/_form_import', [
      'model' => DocumentPosition::findOne(['document_id' => $id]),
      // 'docuement' => $id,
      // 'item_id' => $item_id,
      'dataProvider' => $dataProvider,
    ]);
  }

  public function actionUpload()
  {
    $model = new DocumentPosition();

    if (Yii::$app->request->isPost) {
      $model->imageFile = UploadedFile::getInstance($model, 'txt_path');
      if ($model->upload()) {
        $this->actionImportData($model->getPath());
      }
    }

    // return $this->redirect(['index']);
  }
  public function actionImportData($pathFile)
  {
    $serial_master = [];
    $value = [];
    $path = Yii::getAlias('@webroot') . "/" . $pathFile;
    $fp = fopen($path, "r");
    while ($data = fgetcsv($fp, 1000, ";")) {
      $serial_master[] = $data[0];
      $value[] = $data[1];
      $model = Seriallist::findOne(['serial_master_id' => $data[0]]);
      $model->value = $data[1];
      if ($model->save(false)) {
        Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
          [
            'text' => 'Archivo Guardado',
            'confirmButtonText' => 'Continuar!',
          ]
        ]);
      }
    }
    fclose($fp);


    return $this->redirect(['order/' . $id = 1]);
  }


  public function actionShowSerials()
  {
    $searchModel = new SerialMaster();
    $dataProvider = $searchModel->searchBySerial(Yii::$app->request->queryParams);

    return $this->render('show_serials', [
      'searchModel' => $searchModel,
      'dataProvider' => $dataProvider,
    ]);
  }


  public function actionUnlockAmounts($id)
  {
    // $model = new DocumentPosition();
    $model = $this->findModel($id);
    
    $dataProvider = new ActiveDataProvider([
      'query' => DocumentPosition::find()->where(['document_id' => $id]),
    ]);

    // Validate if there is an editable input saved via AJAX
    if (Yii::$app->request->post('hasEditable')) {

      $keys = Yii::$app->request->post('editableKey');
      $model = DocumentPosition::findOne($keys);

      // Store a default JSON response as desired by editable
      $out = Json::encode(['output' => '', 'message' => '']);
      // Fetch the first entry in posted data (there should only be one
      // entry anyway in this array for an editable submission)

      $values = current($_POST['DocumentPosition']);


      // Load model like any single model validation
      if ($model) {
        // Update the Profile with the new value passed in 
        // $model->real_amount 

        if ( isset($values['real_amount'])) 
        {
          $model->real_amount = $values['real_amount'];
        }

        if (isset( $values['pivot_amount'])) 
        {
          $model->pivot_amount = $values['pivot_amount'];
        }

        $model->save();
      }
      echo $out;
      return;
    }
    return $this->renderAjax('order_locks/manual_block_amounts', [
      'model' => $model,
      'dataProvider' => $dataProvider,
    ]);
  }
  public function actionTest($id)
  {
    $positions= DocumentPosition::find()->where(['document_id'=> $id])->all();
    $header = $this->findModel($id);

    $real_amount=[];
    $pivot_amount=[];
    foreach ($positions as  $value) {
      array_push($real_amount, $value->real_amount);
      array_push($pivot_amount, $value->pivot_amount);

    }
    // var_dump($pivot_amount);
    $diff= array_diff($real_amount, $pivot_amount);
    if (empty($diff)) {
      $header->status=17;
      $header->save();
      $redis = Yii::$app->redis;
      $redis->rpush("exportList",implode(',', [$header->id, $header->docnr]));
      Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
        [
          'title' => 'Pedido Desbloqueado',
          'timer' => 2000,
          'confirmButtonText' => 'Continuar!',
        ]
      ]);
    }else{
      Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_INFO, [
        [
          'title' => 'Pedido Continua Bloqueado',
          'timer' => 2000,
          'confirmButtonText' => 'Continuar!',
        ]
      ]);
    }
    // die();
    $this->redirect([$id]);

  }
  public function actionExportAllRecord($id, $order)
  {
    $sql = "SELECT  [value],  [st].[description] AS descipción 
    ,[item].[code], [document_header].[docnr], [lpn_master].[lpnnr] 
    AS [lpn_pallet], [lm].[lpnnr] 
    AS [lpn_caja], [serial_master].[created_at]
    FROM [serial_master] 
    LEFT JOIN [document_header] [document_header]
    ON document_header.id = serial_master.document_id
    LEFT JOIN [item] [item] 
    ON item.id = serial_master.item_id
    LEFT JOIN [lpn_master] [lpn_master]
    ON lpn_master.id = serial_master.lpn_id 
    LEFT JOIN [lpn_master] [lm] 
    ON lm.id = serial_master.lpn_pos_id 
    LEFT JOIN serial_type st 
    ON st.id = serial_master.type_id 
    WHERE  [document_header].[id]=" . $id;


    $connection = Yii::$app->getDb();
    $command = $connection->createCommand($sql);
    $result = $command->queryAll();


    $base_path = "uploads/Compunet/AllRecord-".$order;

    $wms = new Wms();

    $file = $base_path . date("YmdHis") . ".txt";

    $wms->saveFile($result, $file );
    return Yii::$app->response->sendFile( $file );

  }

  public function actionShowLpns()
  {

    $searchModel = new LpnMaster();
    $dataProvider = $searchModel->searchByOrder(Yii::$app->request->queryParams);

    return $this->render('lpn-master/index', [
      'dataProvider' => $dataProvider,
      'searchModel' => $searchModel,
    ]);
  }
}
