<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%item}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $customer_id
 * @property string|null $code
 * @property int|null $check
 * @property int|null $company_id
 * @property int|null $type
 * @property float|null $netweigth
 * @property string|null $unitnet
 * @property float|null $grweigth
 * @property string|null $unitgrw
 * @property string|null $attributes
 * @property string|null $images
 * @property int|null $created_by
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuxDocPosition[] $auxDocPositions
 * @property Company $company
 * @property User $createdBy
 * @property Customer $customer
 * @property DocumentPosition[] $documentPositions
 * @property LpnMaster[] $lpnMasters
 * @property SerialMaster[] $serialMasters
 * @property SerialRule[] $serialRules
 */
class Item extends \yii\db\ActiveRecord
{
  public $systemStatus;
  public $check;

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return '{{%item}}';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['customer_id', 'check', 'company_id', 'type', 'created_by', 'status'], 'integer'],
      [['netweigth', 'grweigth'], 'number'],
      [['attributes', 'images'], 'string'],
      [['created_at', 'updated_at'], 'safe'],
      [['name', 'description'], 'string', 'max' => 255],
      [['code'], 'string', 'max' => 18],
      [['unitnet', 'unitgrw'], 'string', 'max' => 3],
      [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
      [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
      [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
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
      'customer_id' => Yii::t('app', 'Customer ID'),
      'code' => Yii::t('app', 'Code'),
      'check' => Yii::t('app', 'Check'),
      'company_id' => Yii::t('app', 'Company ID'),
      'type' => Yii::t('app', 'Type'),
      'netweigth' => Yii::t('app', 'Netweigth'),
      'unitnet' => Yii::t('app', 'Unitnet'),
      'grweigth' => Yii::t('app', 'Grweigth'),
      'unitgrw' => Yii::t('app', 'Unitgrw'),
      'attributes' => Yii::t('app', 'Attributes'),
      'images' => Yii::t('app', 'Images'),
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
    return $this->hasMany(AuxDocPosition::className(), ['item_id' => 'id']);
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
   * Gets query for [[Customer]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getCustomer()
  {
    return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
  }

  /**
   * Gets query for [[DocumentPositions]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getDocumentPositions()
  {
    return $this->hasMany(DocumentPosition::className(), ['item_id' => 'id']);
  }

  /**
   * Gets query for [[LpnMasters]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getLpnMasters()
  {
    return $this->hasMany(LpnMaster::className(), ['item_id' => 'id']);
  }

  /**
   * Gets query for [[SerialMasters]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getSerialMasters()
  {
    return $this->hasMany(SerialMaster::className(), ['item_id' => 'id']);
  }

  /**
   * Gets query for [[SerialRules]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getSerialRules()
  {
    return $this->hasMany(SerialRule::className(), ['item_id' => 'id']);
  }

  public function search($params)
  {
    $query = self::find();
    $this->load($params);
    $query->andFilterWhere(['customer_id' => $this->customer_id]);
    $query->andFilterWhere(['like', 'code', $this->code]);
    $query->andFilterWhere(['like', 'name', $this->name]);
    $query->andFilterWhere(['company_id' => $this->company_id]);
    $query->andFilterWhere(['netweigth' => $this->netweigth]);
    $query->andFilterWhere(['unitnet' => $this->unitnet]);
    $query->andFilterWhere(['grweigth' => $this->grweigth]);
    $query->andFilterWhere(['unitgrw' => $this->unitgrw]);


    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
    ]);
    return $dataProvider;
  }

  public function afterFind()
  {
    $system = SystemConfig::findOne(['type' => 'status', 'reference' => 'materials']);
    $system = json_decode($system->value, true);
    // MODO DEMO: Manejar status no definidos en la configuración
    $this->systemStatus = $system[$this->status] ?? ['id' => $this->status, 'label' => 'Activo', 'color' => 'success'];
    $this->check = true;
    parent::afterFind();
  }

}



// {"10":{"id":10,"label":"Activo","color":"success"},
// "11":{"id":11,"label":"En proceso","color":"warning"},
// "12":{"id":12,"label":"Finalizado","color":"success"},
// "14":{"id":14,"label":"Bloqueado","color":"secondary"}}