<?php

namespace backend\models;

use Yii;
use yii\web\UnauthorizedHttpException;

class LpnStock extends \common\models\LpnStock
{
  public function fields()
  {
    return ['id', 'name'];
  }

  public function extraFields()
  {
    return [
      'profile',
    ];
  }
}
