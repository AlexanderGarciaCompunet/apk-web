<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "{{%serial_list}}".
 *
 * @property int $id
 * @property int|null $serial_master_id
 * @property int|null $type_id
 * @property string|null $value
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $docnr
 * @property string $code
 * @property string $lpn_pallet
 * @property string $lpn_caja
 *
 * @property SerialMaster $serialMaster
 * @property SerialType $type
 */
class SerialList extends \yii\db\ActiveRecord
{

  /**
   * {@inheritdoc}
   */
  public $item_id;
  public $document_id;
  public $docnr;
  public $lpn_pallet;
  public $code;
  public $lpn_caja;

  public static function tableName()
  {
    return '{{%serial_list}}';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['serial_master_id', 'type_id', 'status'], 'integer'],
      [['created_at', 'updated_at', 'item_id', 'document_id'], 'safe'],
      [['value', 'docnr', 'code', 'lpn_pallet', 'lpn_caja'], 'string', 'max' => 20],
      [['value'], 'unique'],
      [['serial_master_id'], 'exist', 'skipOnError' => true, 'targetClass' => SerialMaster::className(), 'targetAttribute' => ['serial_master_id' => 'id']],
      [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SerialType::className(), 'targetAttribute' => ['type_id' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => Yii::t('app', 'ID'),
      'serial_master_id' => Yii::t('app', 'Serial Master ID'),
      'type_id' => Yii::t('app', 'Type ID'),
      'value' => Yii::t('app', 'Value'),
      'status' => Yii::t('app', 'Status'),
      'created_at' => Yii::t('app', 'Created At'),
      'updated_at' => Yii::t('app', 'Updated At'),
    ];
  }

  /**
   * Gets query for [[SerialMaster]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getSerialMaster()
  {
    return $this->hasOne(SerialMaster::className(), ['id' => 'serial_master_id']);
  }
  /**
   * Gets query for [[Type]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getType()
  {
    return $this->hasOne(SerialType::className(), ['id' => 'type_id']);
  }



  public function search($params)
  {
    $query = self::find();
    $query->joinWith(['serialMaster']);
    $query->joinWith(['type']);
    $query->leftJoin(['document_header' => DocumentHeader::tableName()], 'document_header.id = serial_master.document_id');
    $query->leftJoin(['item' => Item::tableName()], 'item.id = serial_master.item_id');
    $query->leftJoin(['lpn_master' => LpnMaster::tableName()], 'lpn_master.id = serial_master.lpn_id');
    // $query->leftJoin(['lpn_master as  lm' => LpnMaster::tableName()], 'lm.id = serial_master.lpn_id');
    $query->leftJoin('lpn_master as lm', 'lm.id = serial_master.lpn_pos_id');

    $query->select([
      'serial_list.id',
      'serial_list.value',
      'serial_master.id as serial_master_id',
      'serial_type.description',
      'item.code',
      // 'lpn_master.lpnnr',
      // 'lpn_master.lpnsup',
      'document_header.docnr',
      'lpn_master.lpnnr as lpn_pallet',
      'lm.lpnnr as lpn_caja',
      'serial_list.created_at',
    ]);


    $this->load($params);
    $query->andFilterWhere(['value' => $this->value])
      ->andFilterWhere(['like', 'code', $this->code])
      ->andFilterWhere(['like', 'lpn_master.lpnnr', $this->lpn_pallet])
      ->andFilterWhere(['like', 'lm.lpnnr', $this->lpn_caja])
      ->andFilterWhere(['like', 'docnr', $this->docnr]);


    // var_dump($query->createCommand()->getRawSql());

    if (!is_null($this->created_at) && strpos($this->created_at, ' - ') !== false) {
      list($start_date, $end_date) = explode(' - ', $this->created_at);
      $query->andFilterWhere(['between', 'serial_list.created_at', $start_date, $end_date]);
    }


    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 100,
      ],
      'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
    ]);

    return $dataProvider;
  }
}
