<?php

namespace backend\controllers;

use common\models\SerialList;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class SerialListController extends ActiveController
{
  public $modelClass = 'backend\models\SerialList';

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticatior'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
    ];
    return $behaviors;
  }
}
