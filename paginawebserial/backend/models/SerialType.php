<?php

namespace backend\models;


class SerialType extends \common\models\SerialType
{
  public function fields()
  {
    return ['id', 'description'];
  }
}
