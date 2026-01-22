<?php

namespace backend\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class LpnStockController extends ActiveController
{
  public $modelClass = 'backend\models\LpnStock';

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticatior'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
    ];
    return $behaviors;
  }
}
