<?php

namespace backend\models;

use Yii;
use yii\web\UnauthorizedHttpException;
use common\models\LoginForm;

class LpnPosition extends \common\models\LpnPosition
{
  public function fields()
  {
    return [
      'id',
      'customer_id',
      'lpnnr',
      'posnr',
      'lpnnrax',
      'serialnr',
    ];
  }

  public function extraFields()
  {
    return [
      'profile',
    ];
  }
}
