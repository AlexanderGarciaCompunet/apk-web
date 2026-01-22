<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%aux_doc_header}}".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $aux_doc_id
 * @property int|null $type
 * @property int|null $status_doc
 * @property int|null $store_id
 * @property int|null $created_by
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuxDocPosition[] $auxDocPositions
 * @property User $createdBy
 * @property Customer $customer
 * @property Store $store
 */
class AuxDocHeader extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%aux_doc_header}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'type', 'status_doc', 'store_id', 'created_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['aux_doc_id'], 'string', 'max' => 10],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
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
            'aux_doc_id' => Yii::t('app', 'Aux Doc ID'),
            'type' => Yii::t('app', 'Type'),
            'status_doc' => Yii::t('app', 'Status Doc'),
            'store_id' => Yii::t('app', 'Store ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[AuxDocPositions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuxDocPositions()
    {
        return $this->hasMany(AuxDocPosition::className(), ['aux_doc_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
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
     * Gets query for [[Store]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }
}
