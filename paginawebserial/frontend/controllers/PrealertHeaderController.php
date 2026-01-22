<?php

namespace frontend\controllers;

use common\models\PrealertHeader;
use common\models\PrealertPosition;
use common\models\PrealertPositionSerial;
use common\models\Customer;
use common\models\Item;
use common\models\Store;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * PrealertHeaderController implements the CRUD actions for PrealertHeader model.
 */
class PrealertHeaderController extends Controller
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
   * Lists all PrealertHeader models.
   * @return mixed
   */
  public function actionIndex()
  {
    $model = new PrealertHeader();
    $dataProvider = new ActiveDataProvider([
      'query' => PrealertHeader::find(),
      /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
    ]);


    return $this->render('index', [
      'dataProvider' => $dataProvider,
      'model' => $model,
    ]);
  }

  /**
   * Displays a single PrealertHeader model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {
    $dataProvider = new ActiveDataProvider([
      'query' => PrealertPosition::find()->where(['prealert_id' => $id, 'status' => 10]),
    ]);

    return $this->render('view', [
      'model' => $this->findModel($id),
      'dataProvider' => $dataProvider,
    ]);
  }

  public function actionUpload()
  {
    $model = new PrealertHeader();
    if ($model->load(Yii::$app->request->post())) {
      $model->prealert_text_file = UploadedFile::getInstance($model, 'prealert_text');
      $pathFile = $model->upload();
      if ($pathFile) {
        $this->redirect(["index"]);
        $this->actionImportData($pathFile);
      }
    }
  }

  /**
   * Creates a new PrealertHeader model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new PrealertHeader();

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
   * Updates an existing PrealertHeader model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);

    if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
      $model->text_file = UploadedFile::getInstance($model, 'text');
      $model->upload();
      return $this->redirect(['view', 'id' => $model->id]);
    }
    return $this->render('update', [
      'model' => $model,
    ]);
  }

  /**
   * Deletes an existing PrealertHeader model.
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

  public function isInteger($val)
  {
    if (!is_scalar($val) || is_bool($val)) {
      return false;
    }
    if (is_float($val + 0) && ($val + 0) > PHP_INT_MAX) {
      return false;
    }
    return is_float($val) ? false : preg_match('~^((?:\+|-)?[0-9]+)$~', $val);
  }

  /**
   * Finds the PrealertHeader model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return PrealertHeader the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */

  public function actionImportData($pathFile)
  {
    $key = Yii::$app->params['cryptKey'];
    $path = Yii::getAlias('@webroot') . "/" . $pathFile;
    $items = Item::find()->select(['id', 'code', 'status'])->all();
    $items = ArrayHelper::map($items, 'code', 'id');
    $stores = Store::find()->select(['id', 'code'])->all();
    $stores = ArrayHelper::map($stores, 'code', 'id');
    $customers = Customer::find()->select(['id', 'code'])->all();
    $customers = ArrayHelper::map($customers, 'code', 'id');
    $text = file_get_contents($path, 'r');
    if ($text !== false) {
      $text = explode("\n", $text);
      $i = 0;
      foreach ($text as $line) {
        $line = explode("\t", $line);
        $i += 1;
        if (count($line) != 12) {
          Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
            [
              'title' => "La cantidad de atributos registrados en la línea " . $i . " no corresponde con la estructura.",
              'timer' => 2000,
              'confirmButtonText' => 'Continuar!',
            ]
          ]);
        }
        if (!$this->isInteger($line[0]) || !is_string($line[1]) || !is_string($line[2]) || !is_string($line[3]) || !is_string($line[4]) || !intval($line[5]) || !is_string($line[6]) || !is_string($line[7]) || !is_string($line[8])) {
          // echo $this->isInteger($line[0]);
          Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
            [
              'title' => "La cantidad de atributos registrados en la línea " . $i . " no corresponde con la estructura.",
              'timer' => 2000,
              'confirmButtonText' => 'Continuar!',
            ]
          ]);
        }
      }
      $line0 = explode("\t", $text[0]);
      $store = $line0[0];
      $customer = $line0[1];
      $center = $line0[2];
      $storeClaro = $line0[3];
      $document = $line0[4];
      foreach ($text as $line) {
        $line = explode("\t", $line);
        $i += 1;
        if ($store != $line[0] || $customer != $line[1] || $center != $line[2] || $storeClaro != $line[3] || $document != $line[4]) {
          echo "Existen 2 o mas cabeceras en el archivo de prealerta, la segunda fue encontrada en la línea " . $i . ".";
          die();
        }
      }
      $storeObject = Store::findOne(['code' => $store]);
      $customerObject = Customer::findOne(['code' => $customer]);
      $prealertObject = PrealertHeader::findOne(['store_id' => $stores[$store], 'customer_id' => $customers[$customer], 'center' => $center, 'store' => $storeClaro, 'order_id' => $document]);
      if (!$prealertObject) {
        $prealertObject = new PrealertHeader();
        $prealertObject->store_id = $storeObject->id;
        $prealertObject->customer_id = $customerObject->id;
        $prealertObject->center = $center;
        $prealertObject->store = $storeClaro;
        $prealertObject->order_id = $document;
        $prealertObject->prealert_text = $pathFile;
        $prealertObject->save();
      } else {
        $prealertObject->prealert_text = $pathFile;
        $prealertObject->save();
        $prealertPositions = PrealertPosition::find()->where(['prealert_id' => $prealertObject->id])->all();
        foreach ($prealertPositions as $prealertPositionObject) {
          $prealertPositionObject->status = 9;
          $prealertPositionObject->save();
          $prealertPositionSerials = PrealertPositionSerial::find()->where(['prealert_position_id' => $prealertPositionObject->id])->all();
          foreach ($prealertPositionSerials as $prealertPositionSerialObject) {
            $prealertPositionSerialObject->status = 9;
            $prealertPositionSerialObject->save();
          }
        }
      }
      $i = 0;
      foreach ($text as $line) {
        $line = explode("\t", $line);
        $i += 1;
        $item = $line[5];
        $lpn = $line[6];
        $invsts = $line[7];
        $serial1 = $line[8];
        $serial2 = $line[9];
        $serial3 = $line[10];
        $itemObject = Item::findOne(['code' => $item]);
        $item = $itemObject->id;
        $prealertPositionObject = PrealertPosition::findOne(['prealert_id' => $prealertObject->id, 'item_id' => $item, 'lpn_id' => $lpn, 'invsts' => $invsts, 'status' => 10]);
        if (!$prealertPositionObject) {
          $prealertPositionObject = new PrealertPosition();
          $prealertId = $prealertObject->id;
          $prealertPositionObject->prealert_id = $prealertId;
          $prealertPositionObject->item_id = $item;
          $prealertPositionObject->lpn_id = $lpn;
          $prealertPositionObject->invsts = $invsts;
          $prealertPositionObject->save();
        }
        $prealertPositionSerialObject = PrealertPositionSerial::findOne(['prealert_position_id' => $prealertPositionObject->id, 'serial1' => $serial1, 'serial2' => $serial2, 'serial3' => $serial3, 'status' => 10]);
        if (!$prealertPositionSerialObject) {
          $prealertPositionSerialObject = new PrealertPositionSerial();
          $prealertPositionId = $prealertPositionObject->id;
          $prealertPositionSerialObject->prealert_position_id = $prealertPositionId;
          $prealertPositionSerialObject->serial1 = $serial1;
          $prealertPositionSerialObject->serial2 = $serial2;
          $prealertPositionSerialObject->serial3 = $serial3;
          $prealertPositionSerialObject->save();
        } else {
          Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
            [
              'title' => "Existen 2 o mas posiciones repetidas en el archivo de prealerta, la segunda fue encontrada en la línea " . $i . ".",
              'timer' => 2000,
              'confirmButtonText' => 'Continuar!',
            ]
          ]);
        }
      }
    } else {
      Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
        [
          'title' => "Operacion Fallo",
          'timer' => 2000,
          'confirmButtonText' => 'Continuar!',
        ]
      ]);
    }
  }

  protected function findModel($id)
  {
    if (($model = PrealertHeader::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }
}
