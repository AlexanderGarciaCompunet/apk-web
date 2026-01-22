<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%store}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $code
 * @property string|null $location
 * @property int|null $warehouse_id
 * @property int|null $customer_id
 * @property int|null $created_by
 * @property string|null $other_info
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuxDocHeader[] $auxDocHeaders
 * @property User $createdBy
 * @property Customer $customer
 * @property DocumentHeader[] $documentHeaders
 * @property Warehouse $warehouse
 * @property WorkOrder[] $workOrders
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%store}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'customer_id', 'created_by', 'status'], 'integer'],
            [['other_info'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'description', 'location'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 10],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_id' => 'id']],
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
            'code' => Yii::t('app', 'Code'),
            'location' => Yii::t('app', 'Location'),
            'warehouse_id' => Yii::t('app', 'Warehouse ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'other_info' => Yii::t('app', 'Other Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[AuxDocHeaders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuxDocHeaders()
    {
        return $this->hasMany(AuxDocHeader::className(), ['store_id' => 'id']);
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
   * Gets query for [[StoreUsers]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getStoreUsers()
  {
    return $this->hasMany(StoreUser::className(), ['store_id' => 'id']);
  }

  /**
     * Gets query for [[DocumentHeaders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentHeaders()
    {
        return $this->hasMany(DocumentHeader::className(), ['store_id' => 'id']);
    }

    /**
     * Gets query for [[Warehouse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }

    /**
     * Gets query for [[WorkOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkOrders()
    {
        return $this->hasMany(WorkOrder::className(), ['store_id' => 'id']);
    }
}
