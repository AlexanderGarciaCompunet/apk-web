<?php

namespace backend\models;

use common\models\SerialList;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Yii;

class SerialMaster extends \common\models\SerialMaster
{
  public $serials = [];
  public $pos_id;
  public $arrayObjets = [];


  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    $rules = parent::rules();
    $rules[] = [['serials', 'pos_id'], 'safe'];
    return $rules;
  }

  public function beforeSave($insert)
  {
    foreach ($this->serials as $key => $value) {

      /* if($this->isMatrix()) {
        foreach (json_decode($value) as $row) {
          $model = new SerialList();
          $model->value = $row;
          if (!$model->validate()) {

            return false;
          }
        }
      } else {*/
      $model = new SerialList();
      $model->value = $value;
      if (!$model->validate()) {
        return false;
        //   }
      }
    }
    return parent::beforeSave($insert);
  }
  public function afterSave($insert, $changedAttributes)
  {
    $x = 0;

    $boxstock = LpnBox::findOne(['id' => $this->lpn_pos_id]);

    if ($boxstock) {
      if ($boxstock['real_amount'] < $boxstock['itemcnt']) {
        $boxstock->real_amount += 1;
        $boxstock->update();
      }
    }
    //pedido bloqueado si se pasa itemcnt


    foreach ($this->serials as $key => $value) {


      /* if($this->isMatrix()) {
        foreach (json_decode($value) as $row) {
          $model = new SerialList();
          $model->serial_master_id = $this->id;
          $model->type_id = $key;
          $model->value = $row;
          if ($model->save()) {
            $x++;
          }
        }
      } else {*/
      $model = new SerialList();
      $model->serial_master_id = $this->id;
      $model->type_id = $key;
      $model->value = $value;
      if ($model->save()) {
        $x = 1;
      }
      // }
    }

    if ($x > 0) {
      $elephant = new Client(new Version2X(Yii::$app->params['timesocket']));
      $elephant->initialize();
      // $elephant->emit('setTotalOrder', ['ord_id' => $this->document_id, 'total' => $x]);
      $elephant->emit('setCount', ['pos_id' => $this->pos_id, 'total' => $x]);
      $elephant->close();
      // $posModel = DocumentPosition::findOne($this->pos_id);
      // $posModel->real_amount += $x;
      // $posModel->pivot_amount += $x;
      // $posModel->save();
      Yii::$app->db->createCommand()
        ->update('serial_master', ['status' => 11], 'id = ' . $this->id)
        ->execute();
    }

    parent::afterSave($insert, $changedAttributes);
  }

  private function isMatrix()
  {
    $total = sizeof($this->serials);
    return ($total == 1);
  }

  public function activeFactorySerialMaster()
  {
    return [
      // "id"=> $this->id,
      "document_id" => $this->document_id,
      "pos_id" => $this->pos_id,
      "customer_id" => $this->customer_id,
      "lpn_id" => $this->lpn_id,
      "item_id" => $this->item_id,
      "lpn_pos_id" => $this->lpn_pos_id,
      "type_id" => $this->type_id,
      "value" => $this->value,
      "user_id" => $this->user_id,
      "config_label_id" => $this->config_label_id
    ];
  }
}
