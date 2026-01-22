<?php

namespace backend\controllers;

use backend\models\DocumentHeader;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii;

class DocumentHeaderController extends ActiveController
{
  public $modelClass = 'backend\models\DocumentHeader';

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticatior'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
    ];
    return $behaviors;
  }


  public function actionSearch($type)
  {
    $models = DocumentHeader::findAll(['type' => $type , 'status'=>[12,14]]);
    return $this->generateResponse($models);
  }

  private function generateResponse($model)
  {
    $model = yii\helpers\ArrayHelper::toArray($model);

    $response = [
      'status' => 'error',
      'message' => Yii::t('app', 'Not Found')
    ];

    if ($model) {
      $response =  [
        'status' => 'success',
        'data' => $model
      ];
    }

    // MODO DEMO - Respuesta sin encriptar
    return $response;

    // PRODUCCIÓN - Respuesta encriptada (comentado para demo)
    // $enc = Yii::$app->encrypter->encrypt(json_encode($response));
    // return ['key' => $enc];
  }
}
