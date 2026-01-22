<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%serial_master}}".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $item_id
 * @property string|null $serial_id
 * @property int|null $document_id
 * @property int|null $pos_id
 * @property int|null $lpn_id
 * @property int|null $lpn_pos_id
 * @property int|null $user_id
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AuxDocPosition[] $auxDocPositions
 * @property Customer $customer
 * @property DocumentHeader $document
 * @property Item $item
 * @property LpnPosition[] $lpnPositions
 * @property SerialList[] $serialLists
 * @property User $user
 * @property int|null $config_label_id
 */
class SerialMaster extends \yii\db\ActiveRecord
{
  public $item_id;
  public $document_id;
  public $docnr;
  public $lpn_pallet;
  public $code;
  public $lpn_caja;
  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return '{{%serial_master}}';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['customer_id', 'item_id', 'document_id', 'pos_id', 'lpn_id', 'lpn_pos_id', 'user_id', 'status','type_id' ,'config_label_id'], 'integer'],
      [['created_at', 'updated_at'], 'safe'],
      [['serial_id','value'], 'string', 'max' => 40],
      [['docnr', 'code', 'lpn_pallet', 'lpn_caja'], 'string', 'max' => 20],
      [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SerialType::className(), 'targetAttribute' => ['type_id' => 'id']],
      [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
      [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentHeader::className(), 'targetAttribute' => ['document_id' => 'id']],
      [['pos_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentPosition::className(), 'targetAttribute' => ['pos_id' => 'id']],
      [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
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
      'item_id' => Yii::t('app', 'Item ID'),
      'serial_id' => Yii::t('app', 'Serial ID'),
      'type_id' => Yii::t('app', 'Type ID'),
      'value' => Yii::t('app', 'Value'),
      'document_id' => Yii::t('app', 'Document ID'),
      'pos_id' => Yii::t('app', 'Position ID'),
      'lpn_id' => Yii::t('app', 'Lpn ID'),
      'lpn_pos_id' => Yii::t('app', 'Lpn Pos ID'),
      'user_id' => Yii::t('app', 'User ID'),
      'config_label_id' => Yii::t('app', 'Config Label ID'),
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
    return $this->hasMany(AuxDocPosition::className(), ['serial_id' => 'id']);
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
   * Gets query for [[Document]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getDocument()
  {
    return $this->hasOne(DocumentHeader::className(), ['id' => 'document_id']);
  }

  public function getDocumentPosition()
  {
    return $this->hasOne(DocumentPosition::className(), ['id' => 'pos_id']);
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
   * Gets query for [[LpnPositions]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getLpnPositions()
  {
    return $this->hasMany(LpnPosition::className(), ['serial_id' => 'id']);
  }

  /**
   * Gets query for [[SerialLists]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getSerialLists()
  {
    return $this->hasMany(SerialList::className(), ['serial_master_id' => 'id']);
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
  public function search($params)
  {
    $query = self::find();
    $this->load($params);

    $query->andFilterWhere(['>=', 'updated_at', $this->updated_at]);
    $query->andFilterWhere(['<=', 'updated_at', $this->updated_at]);

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      // 'sort' => ['defaultOrder' => ['docnr' => SORT_ASC]]
    ]);

    return $dataProvider;
  }

  public function searchBySerial($params)
  {
    $query = self::find();
    $query->leftJoin(['document_header' => DocumentHeader::tableName()], 'document_header.id = serial_master.document_id');
    $query->leftJoin(['item' => Item::tableName()], 'item.id = serial_master.item_id');
    $query->leftJoin(['lpn_master' => LpnMaster::tableName()], 'lpn_master.id = serial_master.lpn_id');
    $query->leftJoin('lpn_master as lm', 'lm.id = serial_master.lpn_pos_id');

    $query->select([
      'serial_master.id',
      'value',
      'item.code',
      'document_header.docnr',
      'lpn_master.lpnnr as lpn_pallet',
      'lm.lpnnr as lpn_caja',
      'serial_master.created_at',
    ]);


    $this->load($params);
    $query->andFilterWhere(['value' => $this->value])
      ->andFilterWhere(['like', 'code', $this->code])
      ->andFilterWhere(['like', 'lpn_master.lpnnr', $this->lpn_pallet])
      ->andFilterWhere(['like', 'lm.lpnnr', $this->lpn_caja])
      ->andFilterWhere(['like', 'docnr', $this->docnr]);



    if (!is_null($this->created_at) && strpos($this->created_at, ' - ') !== false) {
      list($start_date, $end_date) = explode(' - ', $this->created_at);
      $query->andFilterWhere(['between', 'serial_master.created_at', $start_date, $end_date]);
    }


    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 500,
      ],
      'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
    ]);

    return $dataProvider;
  }
}
