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

class StoreAssignment extends BaseObject
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
   * @var string attribute Name
   */
  public $attribute;

  public $target;

  /**
   * @inheritdoc
   */
  public function __construct($id, $user = null, $attribute = 'fullName', $target=User::class, $config = array())
  {
    $this->id = $id;
    $this->user = $user;
    $this->attribute = $attribute;
    $this->target = $target;
    parent::__construct($config);
  }

  public function setTarget($target)
  {
    $this->target = $target;
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
        $item = new StoreUser();
        $item->user_id = ($this->target == User::class) ? $name : $this->id;
        $item->store_id = ($this->target == User::class) ? $this->id : $name;
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
        $user_id = ($this->target == User::class) ? $name : $this->id;
        $store_id = ($this->target == User::class) ? $this->id : $name;
        $item = StoreUser::findOne(['store_id' => $store_id, 'user_id' => $user_id]);
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
    $column = ($this->target == User::class) ? 'user_id' : 'store_id';
    $sub = StoreUser::find()->select($column)->where(['user_id' => $this->user->id])->asArray()->all();
    $sub = ArrayHelper::getColumn($sub,$column);
    $users = $this->target::find()->all();
    $available = [];
    $assigned = [];
    $attribute = $this->attribute;

    foreach ($users as $name) {
      $exist = in_array($name->id, $sub);

      if ($exist) {
        $assigned[$name->id] = $name->$attribute;
      } else {
        $available[$name->id] = $name->$attribute;
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
