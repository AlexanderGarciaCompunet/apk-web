<?php

namespace backend\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii;

class LpnPositionController extends ActiveController
{
  public $modelClass = 'backend\models\LpnPosition';

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticatior'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
    ];
    return $behaviors;
  }

  public function actionSearch()
  {
    $params = Yii::$app->getRequest()->getBodyParams();
    // var_dump($params['id']);
    $id = $params['id'];
    var_dump($id);

    die();
    $sql = 'SELECT id FROM lpn_master';
    $res = Yii::$app->db->createCommand($sql)->queryAll();
    foreach ($res as $ress) {
      // var_dump($ress['id']);
      if ($ress['id'] == $id) {
        // return true;
        return [
          'status' => 'true'
        ];
      } else {
        return [
          'status' => 'false'
        ];
      }
    }
  }
}
