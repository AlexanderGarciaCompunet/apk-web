<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $description
 * @property string|null $location
 * @property int|null $company_id
 * @property int|null $created_by
 * @property string|null $other_info
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuxDocHeader[] $auxDocHeaders
 * @property AuxDocPosition[] $auxDocPositions
 * @property Company $company
 * @property User $createdBy
 * @property DocumentHeader[] $documentHeaders
 * @property DocumentPosition[] $documentPositions
 * @property Item[] $items
 * @property LpnMaster[] $lpnMasters
 * @property LpnPosition[] $lpnPositions
 * @property LpnStock[] $lpnStocks
 * @property SerialMaster[] $serialMasters
 * @property SerialRule[] $serialRules
 * @property Store[] $stores
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'created_by', 'status'], 'integer'],
            [['other_info'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 10],
            [['name', 'description', 'location'], 'string', 'max' => 255],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'location' => Yii::t('app', 'Location'),
            'company_id' => Yii::t('app', 'Company ID'),
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
        return $this->hasMany(AuxDocHeader::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[AuxDocPositions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuxDocPositions()
    {
        return $this->hasMany(AuxDocPosition::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
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
     * Gets query for [[DocumentHeaders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentHeaders()
    {
        return $this->hasMany(DocumentHeader::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[DocumentPositions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentPositions()
    {
        return $this->hasMany(DocumentPosition::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[LpnMasters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLpnMasters()
    {
        return $this->hasMany(LpnMaster::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[LpnPositions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLpnPositions()
    {
        return $this->hasMany(LpnPosition::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[LpnStocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLpnStocks()
    {
        return $this->hasMany(LpnStock::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[SerialMasters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSerialMasters()
    {
        return $this->hasMany(SerialMaster::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[SerialRules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSerialRules()
    {
        return $this->hasMany(SerialRule::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Stores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasMany(Store::className(), ['customer_id' => 'id']);
    }
}
