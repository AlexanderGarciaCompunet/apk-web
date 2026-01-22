<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lpn_stock}}".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $lpnnr
 * @property string|null $dateto
 * @property string|null $datefrom
 * @property int|null $boxstock
 * @property int|null $unistock
 * @property int|null $user_id
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $customer
 * @property User $user
 */
class LpnStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lpn_stock}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'boxstock', 'unistock', 'user_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['lpnnr'], 'string', 'max' => 18],
            [['dateto', 'datefrom'], 'string', 'max' => 8],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
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
            'dateto' => Yii::t('app', 'Dateto'),
            'datefrom' => Yii::t('app', 'Datefrom'),
            'boxstock' => Yii::t('app', 'Boxstock'),
            'unistock' => Yii::t('app', 'Unistock'),
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
