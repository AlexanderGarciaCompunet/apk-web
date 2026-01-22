<?php

namespace backend\controllers;

use backend\models\SerialType;
use common\models\SerialRules;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii;

class SerialRulesController extends ActiveController
{
  public $modelClass = 'common\models\SerialRules';


  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticatior'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
    ];
    return $behaviors;
  }
  public function actionSearch($item)
  {

    $aux = [];
    $temp = array();
    $temp2 = array();

    $isItem = SerialRules::find()
      ->where(['item_id' => $item])
      ->exists();



    if ($isItem) {

      $sql = "select cl.* ,r.groups ,r.orientation ,c.id as config
        from serial_rules sr 
        inner join config_label cl 
        on sr.config_label_id = cl.id 
        inner join reference r 
        on cl.reference_id =r.id 
        inner join config c 
        on cl.config_id = c.id 
        WHERE sr.item_id=" . $item;
      $conection = Yii::$app->getDb();
      $command = $conection->createCommand($sql);
      $result = $command->queryAll();


      $type = SerialType::find()->select('description')->column();

      foreach ($result as $key => $value) {
        $result = [];
        $aux = [
          "idTag" => intval($value['id']),
          "name" => $value['name'],
          "description" => $value['description'],
          "qtColumns" => intval($value['qtColumns']),
          "groups" => intval($value['groups']),
          "orientation" => intval($value['orientation']),
          "epsilon" => floatval($value['epsilon']),
          "time" => intval($value['time']),
          "configId" => intval($value['config']),
          "typeCapture" => 0,
        ];

        if (!empty($value['serialty'])) {
          foreach (json_decode($value['serialty'], true) as $key) {
            if (isset($key) && $key != "") {
              $result[$key] = $type[$key - 1]; //se guardan toda la estructura "struct": {"id tipo serial" :"descripcion" }
              $aux['serialty'] = $result;
            }
          }
          array_push($temp, $result);
          array_push($temp2, $aux);
        }
      }
    }


    return $this->generateResponse($temp2);
  }

  private function generateResponse($data)
  {
    if ($data) {
      $response = [
        "status" => "success",
        "data" => $data,
      ];
    } else {
      $response = [
        'status' => 'error',
        'message' => Yii::t('app', 'Not Found')
      ];
    }
    $enc = Yii::$app->encrypter->encrypt(json_encode($response));
    return [
      'key' => $enc
    ];
  }
}
