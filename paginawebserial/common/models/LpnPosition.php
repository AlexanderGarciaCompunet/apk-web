<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lpn_position}}".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $lpnnr
 * @property int|null $posnr
 * @property int|null $lpnnrax
 * @property int|null $serial_id
 * @property int|null $user_id
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $customer
 * @property LpnMaster $lpnnr0
 * @property SerialMaster $serial
 * @property User $user
 */
class LpnPosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lpn_position}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'lpnnr', 'posnr', 'lpnnrax', 'serial_id', 'user_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['lpnnr'], 'exist', 'skipOnError' => true, 'targetClass' => LpnMaster::className(), 'targetAttribute' => ['lpnnr' => 'id']],
            [['serial_id'], 'exist', 'skipOnError' => true, 'targetClass' => SerialMaster::className(), 'targetAttribute' => ['serial_id' => 'id']],
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
            'customer_id' => Yii::t('app', 'Customer ID'),
            'lpnnr' => Yii::t('app', 'Lpnnr'),
            'posnr' => Yii::t('app', 'Posnr'),
            'lpnnrax' => Yii::t('app', 'Lpnnrax'),
            'serial_id' => Yii::t('app', 'Serial ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
     * Gets query for [[Lpnnr0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLpnnr0()
    {
        return $this->hasOne(LpnMaster::className(), ['id' => 'lpnnr']);
    }

    /**
     * Gets query for [[Serial]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSerial()
    {
        return $this->hasOne(SerialMaster::className(), ['id' => 'serial_id']);
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
