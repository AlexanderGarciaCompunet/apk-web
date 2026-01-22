<?php

namespace frontend\controllers;

use common\models\DocumentHeader;
use console\models\Wms;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * RuleController implements the CRUD actions for SerialRules model.
 */
class ScriptsController extends Controller
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
   * Lists all SerialRules models.
   * @return mixed
   */
  public function actionIndex()
  {
    $model = DocumentHeader::find()->where(['status' => 15])->all();

    return $this->render('index', [
      'model' => $model,
    ]);
  }


  public function actionListScript()
  {
    $searchModel = new DocumentHeader();
    $dataProvider = $searchModel->searchSt15(Yii::$app->request->queryParams);
    return $this->render(
      'script_list',
      [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
      ]
    );
  }

  public function actionPrealertList()
  {
    $searchModel = new DocumentHeader();
    $dataProvider = $searchModel->searchSt15(Yii::$app->request->queryParams);
    return $this->render(
      'prealert_list',
      [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
      ]
    );
  }

  public function actionExportData($id)
  {
    $base_path = "uploads/Compunet/SERMAS";
    $wms = new Wms();
    $model = new  DocumentHeader();
    $data = $model->exportDocument($id);

    $file = $base_path . date("YmdHis") . ".txt";

    $wms->saveFile($data,$file);
    return Yii::$app->response->sendFile($file);

  }

  public function actionExportPrealert($id)
  {

    $base_path = "uploads/Compunet/PRE";
    $wms = new Wms();
    $model = new  DocumentHeader();
    $data = $model->exportPrealert($id);

    $file = $base_path . date("YmdHis") . ".txt";


    $wms->saveFile($data,  $file);
    return Yii::$app->response->sendFile(  $file);
    // $this->redirect(['scripts/list-script']);
  }


  public function showMessageImport($path)
  {
  
    Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
      [
        'title' => 'Importación finalizada',
        'text' => 'Ruta : ' .  $path . date("YmdHis") . ".txt",
        // 'timer' => 5000,
        'confirmButtonText' => 'Continuar!',
      ]
    ]);
  }
  public function actionDownload($id)
  {
    if (file_exists($id)) {
      die();
      Yii::$app->response->sendFile($id);
      unlink($id);
    }
    // die();
    // Yii::$app->session->setFlash('success', 'Importación finalizada');
  }
}
