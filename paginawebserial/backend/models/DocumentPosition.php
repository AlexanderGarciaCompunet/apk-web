<?php

namespace backend\models;

class DocumentPosition extends \common\models\DocumentPosition
{
  public function fields()
  {
    return [
      'id',
      'document_id',
      'posnr',
      'item_id',
      'code' => 'Code',
      'unit',
      'amount',
      'rcvsts',
      'real_amount',
      'customer_id'
    ];
  }
  public function getCode()
  {
    return  $this->item->code;
  }
  public function extraFields()
  {
    return [
      'id',
    ];
  }
}
