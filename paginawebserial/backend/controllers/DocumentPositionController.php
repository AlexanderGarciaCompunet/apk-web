<?php

namespace backend\controllers;

use backend\models\DocumentPosition;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii;

class DocumentPositionController extends ActiveController
{
  public $modelClass = 'backend\models\DocumentPosition';

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticatior'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
    ];
    return $behaviors;
  }

  public function actionSearch($id)
  {
    $customers = DocumentPosition::findAll(['document_id' => $id]);
    return $this->generateResponse($customers);
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

    $enc = Yii::$app->encrypter->encrypt(json_encode($response));
    return [
      'key' => $enc
    ];
  }
}
