<?php

namespace backend\models;

class SerialList extends \common\models\SerialList
{
  public function fields()
  {
    return ['id'];
  }
  public function extraFields()
  {
    return [
      'profile',
    ];
  }
  public function activeFactorySerialList()
  {
    return [
      "type_id"=> $this->type_id,
      "value"=> $this->value,
      "serial_master_id"=> $this->serial_master_id,
    ];
  }
}
