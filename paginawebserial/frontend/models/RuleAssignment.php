<?php


namespace frontend\models;

use common\models\ConfigLabel;
use common\models\SerialRules;
use common\models\StoreUser;
use common\models\User;
use common\models\WorkOrder;
use mdm\admin\components\Configs;
use mdm\admin\components\Helper;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class RuleAssignment extends BaseObject
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
  public function __construct($id, $user = null, $attribute = 'id', $target = SerialRules::class, $config = array())
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
    // var_dump($this->user->customer_id);
    $success = 0;
    foreach ($items as $name) {
      try {
        $item = new SerialRules();
        $item->config_label_id= $name;
        $item->item_id= $this->id;
        $item->customer_id= $this->user->customer_id;
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
        $item = SerialRules::findOne(['item_id' => $this->id, 'config_label_id' => $name]);
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
    $column = ($this->target == ConfigLabel::class) ?  'config_label_id':'config_label_id';
    $sub = SerialRules::find()->select($column)->where(['item_id' =>$this->id])->asArray()->all();
    
    $sub = ArrayHelper::getColumn($sub, $column);
    $config = $this->target::find()->all();
    
    $available = [];
    $assigned = [];
    $attribute = $this->attribute;
    foreach ($config as $name) {
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
