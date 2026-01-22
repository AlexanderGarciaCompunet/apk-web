<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%company}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $license
 * @property string|null $valid_date
 * @property string|null $other_info
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer[] $customers
 * @property Item[] $items
 * @property User[] $users
 * @property Warehouse[] $warehouses
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['valid_date', 'created_at', 'updated_at'], 'safe'],
            [['other_info'], 'string'],
            [['status'], 'integer'],
            [['name', 'description', 'license'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'license' => Yii::t('app', 'License'),
            'valid_date' => Yii::t('app', 'Valid Date'),
            'other_info' => Yii::t('app', 'Other Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Warehouses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['company_id' => 'id']);
    }
}
