<?php

namespace backend\controllers;

use Yii;
use backend\models\Store;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class StoreController extends ActiveController
{
  public $modelClass = 'backend\models\Store';

  public function actions()
  {
    $actions = parent::actions();
    unset($actions['index']);

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

  public function actionIndex()
  {
    $model = Store::find()->all();
    $model =  yii\helpers\ArrayHelper::toArray($model);
    $enc = Yii::$app->encrypter->encrypt(json_encode($model));
    return [
      'key' => $enc
    ];
    return $model;
  }
}
