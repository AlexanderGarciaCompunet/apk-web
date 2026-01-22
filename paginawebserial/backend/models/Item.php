<?php
namespace backend\models;

use common\models\SystemConfig;
use Yii;
use yii\web\UnauthorizedHttpException;

class Item extends \common\models\Item
{
    public function fields()
    {
        return ['id', 'name', 'type' => 'typeTC',];
    }

    /*
    public function extraFields()
    {
        return [
            'profile',
        ];
    }*/

  public function getTypeTC(){
    $result = "N/A";
    $model = SystemConfig::findOne(['type' => 'unit', 'reference' => 'tc001']);

    if ($model) {
      $decode = json_decode($model->value, true);
      $key  = $this->type;
      $result = $decode[$key]['unittx'];
    }

    return $result;
  }

}
