<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%aux_doc_position}}".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $aux_doc_id
 * @property int|null $item_id
 * @property int|null $serial_id
 * @property int|null $created_by
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuxDocHeader $auxDoc
 * @property User $createdBy
 * @property Customer $customer
 * @property Item $item
 * @property SerialMaster $serial
 */
class AuxDocPosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%aux_doc_position}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'aux_doc_id', 'item_id', 'serial_id', 'created_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['aux_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuxDocHeader::className(), 'targetAttribute' => ['aux_doc_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['serial_id'], 'exist', 'skipOnError' => true, 'targetClass' => SerialMaster::className(), 'targetAttribute' => ['serial_id' => 'id']],
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
            'item_id' => Yii::t('app', 'Item ID'),
            'serial_id' => Yii::t('app', 'Serial ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[AuxDoc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuxDoc()
    {
        return $this->hasOne(AuxDocHeader::className(), ['id' => 'aux_doc_id']);
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
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
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
}
