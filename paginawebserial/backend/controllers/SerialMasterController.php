<?php

namespace backend\controllers;

use backend\models\DocumentPosition;
use backend\models\LpnMaster;
use backend\models\SerialMaster;
use backend\models\DocumentHeader;
use backend\models\Item;
use backend\models\LpnBox;
use common\models\WorkOrder;
use common\models\SerialMaster as ModelsSerialMaster;
use yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use yii\helpers\VarDumper;

class SerialMasterController extends ActiveController
{
  private  $code = [120, 121, 122];

  public $modelClass = 'backend\models\SerialMaster';
  private $item_id;
  private $pos_id;
  private $document_id;
  private $customer_id;
  private $lpn_id;
  private $lpn_pos_id;
  private $config_label_id;

  public function actions()
  {
    $actions = parent::actions();
    unset($actions['delete']);
    unset($actions['create']);
    return $actions;
  }

  public function behaviors()
  {

    $behaviors = parent::behaviors();
    $behaviors['authenticatior'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
    ];
    return $behaviors;
  }

  public function actionCreate()
  {
    $x = 0;
    $model = new SerialMaster();
    $model->load(Yii::$app->getRequest()->getBodyParams());
    $pivot = null;
    $serials = null;
    $params = Yii::$app->getRequest()->getBodyParams();

    // MODO DEMO - Sin encriptación
    // Si viene con 'key', intenta desencriptar, si no, usa params directo
    if (isset($params['key']) && isset(Yii::$app->encrypter)) {
      try {
        $params = json_decode(Yii::$app->encrypter->decrypt($params['key']), true);
      } catch (\Exception $e) {
        // Si falla, asume que viene sin encriptar
        // No hace nada, mantiene $params
      }
    }



    $this->item_id = $params['item_id'];
    $this->document_id = $params['document_id'];
    $this->pos_id = $params['pos_id'];
    $this->customer_id = $params['customer_id'];
    $this->lpn_id = $params['lpn_id'];
    $this->lpn_pos_id = $params['lpn_pos_id'];
    $this->pos_id = $params['pos_id'];
    $data = $params['serials'];
    $this->config_label_id = $params['config_label_id'];
    $insert = [];
    $arraySerialMaster = [];
    $max = 0;

    foreach ($data as $key => $value) {
      $serials = json_decode($value);
      $repeats = ArrayHelper::getColumn(SerialMaster::find()->where(['value' => $serials, 'document_id'=>$this->document_id])->all(), 'value');
      foreach ($repeats as $row) {
        ArrayHelper::removeValue($serials, $row);
      }

      if(sizeof($serials)>$max){
        $max = sizeof($serials);
      }

      foreach ($serials as $row) {
        if ($key == 100) {
          $insert[] = [100, $row, $pivot];
        } else {
          $model = new SerialMaster();
          $model->document_id = $this->document_id;
          $model->pos_id = $this->pos_id;
          $model->customer_id = $this->customer_id;
          $model->lpn_id = $this->lpn_id;
          $model->item_id = $this->item_id;
          $model->lpn_pos_id = $this->lpn_pos_id;
          $model->config_label_id = $this->config_label_id;
          $model->user_id = Yii::$app->user->identity->id;
          $model->type_id = $key;
          $model->value = $row;
          array_push($arraySerialMaster, $model->activeFactorySerialMaster());
          if (!$pivot) {
            $pivot = $model->id;
          }
        }
      }
    }
   

    Yii::$app->db->createCommand()->batchInsert(
      'serial_master',
      [
        "document_id",
        "pos_id",
        "customer_id",
        "lpn_id",
        "item_id",
        "lpn_pos_id",
        "type_id",
        "value",
        "user_id",
        "config_label_id"
      ],
      $arraySerialMaster
    )->execute();



    


    // $lpn = LpnMaster::findOne($this->lpn_id);
    // if (isset($lpn)) {
    //   $lpn->real_amount +=  sizeof($arraySerialMaster);
    //   $lpn->update();
    // }

    $boxstock = LpnBox::findOne(['id' => $this->lpn_pos_id]);

    if ($boxstock) {
      if ($boxstock['real_amount'] < $boxstock['itemcnt']) {
        $boxstock->real_amount =  $max;
        $boxstock->update();
      }
    }

    
    $position = DocumentPosition::findOne($this->pos_id);
    if (isset($position)) {
      
    $position->real_amount +=  $max;
    // $position->pivot_amount +=  $max;
    $position->update();
    }

    $position = DocumentPosition::findOne($this->pos_id);
    if ($position->status == 12) {
      $position->status = 14;
      $order = DocumentHeader::findOne($position->document_id);
      if ($order->status == 12) {
        $order->work_init = date("Y-m-d H:i:s");
        $order->status = 14;
        $order->save();
      }
      $position->save();
    }




     $elephant = new Client(new Version2X(Yii::$app->params['timesocket']));
     $elephant->initialize();
    //  $elephant->emit('setTotalOrder', ['ord_id' => $this->document_id, 'total' => $max]);
     $elephant->emit('setCount', ['pos_id' => $this->pos_id, 'total' =>$position->real_amount]);
     $elephant->close();

   

    if (sizeof($repeats)) {
      $response = [
        'status' => 'error',
        'message' => '122'
      ];
    }else{
      $response = [
        'status' => 'success',
        'message' => '120'

      ];
    }

    // MODO DEMO - Respuesta sin encriptar
    return $response;

    // PRODUCCIÓN - Respuesta encriptada (comentado para demo)
    // $enc = Yii::$app->encrypter->encrypt(json_encode($response));
    // return ['key' => $enc];

    /*$boxstock = LpnMaster::findOne(['id' => $this->lpn_pos_id]);

    if ($boxstock['real_amount'] == $boxstock['itemcnt']) {
      $boxstock->status  = 12;
      $boxstock->update();
      return $this->generateResponse($boxstock);
    } else {
      $model->save();
      return $this->generateResponse($model);
    }*/
  }
  public function getMaxbyArray($array)
  
  { 
   return array_map(function ($value){
    return json_decode($value ,true);
  },
  $array
);
  }


  public function actionFinish($id)
  {

    $user  = Yii::$app->user->identity->id;
    // var_dump($user);
    // die();

    //variable donde se guarda la coincidencia entre el amount y el real_amount
    $total = 0;
    $closeOrder = [];

    $documentPosition = DocumentPosition::find()
      ->where(['document_id' => $id])
      // ->andWhere(['status' => 14])
      ->all();


    $orderByUser = WorkOrder::find()
    ->where(['user_id'=>$user])
    ->andWhere(['order_id'=>$id])
    ->one();

   


    $orderByUser->status= 15;
    $orderByUser->save();

    $statusByuser= WorkOrder::find()->where(['order_id '=>$id , 'status'=>10])->all();


    // var_dump(sizeof($statusByuser)==0);
    // die();
    if(sizeof($statusByuser)==0){

 //se cambia el valor de el estado 14 a estado 15 
 foreach ($documentPosition as  $value) {
  $value->status = 15;
  $value->save();
  if ($value->amount == $value->real_amount) { //si hay coincidencias se suma al total
    $total++;
  }
}
foreach ($documentPosition as  $value) {
  $document = DocumentHeader::find()->where(['id' => $value->document_id])->one();
  if ($total != count($documentPosition) && $total != 0) { //se evalua si la cantidad es coincidencias es diferente a la cantidad de posiciones
    $document->status = 19;
    $document->save();
    $closeOrder =  [
      'status' => 'locked',
      'message' => $this->code[0]
    ];
  } else {
    $document->status = 17;
    $document->save();
    $redis = Yii::$app->redis;
    $redis->rpush("exportList",implode(',', [$document->id, $document->docnr]));
    $closeOrder =  [
      'status' => 'closed',
      'message' => $this->code[1]
    ];
  }
}
    }

    
   
    $enc = $closeOrder;
    return [
      'key' => $enc

    ];
  }

  private function generateResponse($model, $code)
  {
    if (!$model) {
      $response =  [
        'status' => 'locked',
        'message' => $this->code[$code]
      ];
    } else {
      $response = [
        'status' => 'closed',
        'message' => $this->code[$code]
      ];
    }
    return $response;
  }

  private function match($v)
  {
    return $v;
  }
}
