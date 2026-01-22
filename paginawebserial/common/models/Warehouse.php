<?php

namespace common\models;

use seisvalt\changeLogBehavior\ChangeLogBehavior;
use Yii;

/**
 * This is the model class for table "{{%warehouse}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $code
 * @property string|null $location
 * @property int|null $company_id
 * @property int|null $created_by
 * @property string|null $other_info
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Company $company
 * @property User $createdBy
 * @property DocumentHeader[] $documentHeaders
 * @property Store[] $stores
 */
class Warehouse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%warehouse}}';
    }

    public function behaviors()
    {
      return [
       /* 'encryption' => [
          'class' => '\arknova\encrypter\behaviors\EncryptionBehavior',
          'attributes' => [
            'description',
            //'code',
          ],
        ],*/
        [
          'class' => ChangeLogBehavior::className(),
          'excludedAttributes' => ['updated_at'],
        ],
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['company_id', 'created_by', 'status'], 'integer'],
            [['other_info'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'description', 'location'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 4],
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
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'code' => Yii::t('app', 'Code'),
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
        return $this->hasMany(DocumentHeader::className(), ['warehouse_id' => 'id']);
    }

    /**
     * Gets query for [[Stores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasMany(Store::className(), ['warehouse_id' => 'id']);
    }
}
