<?php


namespace frontend\models;

use common\models\StoreUser;
use common\models\User;
use common\models\WorkOrder;
use mdm\admin\components\Configs;
use mdm\admin\components\Helper;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class OrderAssignment extends BaseObject
{
  /**
   * @var integer User id
   */
  public $id;
  /**
   * @var \yii\web\IdentityInterface User
   */
  public $user;

  /**
   * @inheritdoc
   */
  public function __construct($id, $user = null, $config = array())
  {
    $this->id = $id;
    $this->user = $user;
    parent::__construct($config);
  }

  /**
   * Grands
   * @param array $items
   * @return integer number of successful grand
   */
  public function assign($items)
  {
    $success = 0;

    foreach ($items as $name) {
      try {
        $item = new WorkOrder();
        $item->user_id = $name;
        $item->order_id = $this->id;
        $item->save();
        $success++;
      } catch (\Exception $exc) {
        Yii::error($exc->getMessage(), __METHOD__);
      }
    }
    Helper::invalidate();
    return $success;
  }

  /**
   * Revokes
   * @param array $items
   * @return integer number of successful revoke
   */
  public function revoke($items)
  {
    $success = 0;
    foreach ($items as $name) {
      try {
        $item = WorkOrder::findOne(['order_id' => $this->id, 'user_id' => $name]);
        $item->delete();
        $success++;
      } catch (\Exception $exc) {
        Yii::error($exc->getMessage(), __METHOD__);
      }
    }
    Helper::invalidate();
    return $success;
  }

  /**
   * Get all available and assigned users
   * @return array
   */
  public function getItems()
  {
    $sub = WorkOrder::find()->select('user_id')->where(['order_id' => $this->id])->asArray()->all();
    $sub = ArrayHelper::getColumn($sub, 'user_id');
    $users = User::find()->all();
    $available = [];
    $assigned = [];


    foreach ($users as $name) {

      $exist = in_array($name->id, $sub);

      if ($exist) {
        $assigned[$name->id] = $name->fullName;
      } else {
        $available[$name->id] = $name->fullName;
      }
    }

    ksort($available);
    ksort($assigned);

    return [
      'available' => $available,
      'assigned' => $assigned,
    ];
  }

  /**
   * @inheritdoc
   */
  public function __get($name)
  {
    if ($this->user) {
      return $this->user->$name;
    }
  }
}
