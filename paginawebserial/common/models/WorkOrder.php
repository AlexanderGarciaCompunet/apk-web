<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property int|null $priority
 * @property int|null $type
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $other_info
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DocumentHeader $order
 * @property User $user
 */
class WorkOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'order_id'], 'required'],
            [['user_id', 'order_id', 'priority', 'type', 'status'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['other_info'], 'string'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentHeader::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'priority' => Yii::t('app', 'Priority'),
            'type' => Yii::t('app', 'Type'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'other_info' => Yii::t('app', 'Other Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(DocumentHeader::className(), ['id' => 'order_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
