<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "serial_rules".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $item_id
 * @property int|null $status
 * @property string $created_at
 * @property string|null $updated_at
 * @property int|null $time
 * @property int|null $config_label_id
 *
 * @property ConfigLabel $configLabel
 * @property Customer $customer
 * @property Item $item
 */
class SerialRules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'serial_rules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'item_id', 'status', 'time', 'config_label_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['config_label_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConfigLabel::className(), 'targetAttribute' => ['config_label_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'time' => Yii::t('app', 'Time'),
            'config_label_id' => Yii::t('app', 'Config Label ID'),
        ];
    }

    /**
     * Gets query for [[ConfigLabel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfigLabel()
    {
        return $this->hasOne(ConfigLabel::className(), ['id' => 'config_label_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
