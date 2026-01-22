<?php

namespace backend\models;

class LpnMaster extends \common\models\LpnMaster
{
  public $lpns = [];

  public function fields()
  {
    return [
      'id',
      'customer_id',
      'lpnnr',
      'lpnty',
      'document_id',
      'itemcnt',
      'user_id',
      'real_amount'
    ];
  }

  public function afterSave($insert, $changedAttributes)
  {
    // lpn sub 
    $lpnsup = Lpnmaster::find()
      ->where(['id' => $this->lpnsup])
      ->one();

    if ($lpnsup != NULL) {
      $lpnsup['real_amount'] += 1;
      $lpnsup->update();
    }

    parent::afterSave($insert, $changedAttributes);
  }
}
