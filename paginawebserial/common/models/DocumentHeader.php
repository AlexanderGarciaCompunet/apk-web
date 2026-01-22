<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "{{%document_header}}".
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $docnr
 * @property int|null $warehouse_id
 * @property int|null $store_id
 * @property int|null $type
 * @property int|null $docaxnr
 * @property int|null $created_by
 * @property string $work_init
 * @property string $work_end
 * @property string|null $orgcod
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property Customer $customer
 * @property DocumentPosition[] $documentPositions
 * @property LpnMaster[] $lpnMasters
 * @property SerialMaster[] $serialMasters
 * @property Store $store
 * @property Warehouse $warehouse
 * @property WorkOrder[] $workOrders
 */
class DocumentHeader extends \yii\db\ActiveRecord
{
  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return '{{%document_header}}';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['customer_id'], 'required'],
      [['customer_id', 'warehouse_id', 'store_id', 'type', 'docaxnr', 'created_by', 'status'], 'integer'],
      [['work_init', 'work_end', 'created_at', 'updated_at',], 'safe'],
      [['docnr'], 'string', 'max' => 35],
      [['orgcod'], 'string', 'max' => 10],
      [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
      [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
      [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
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
      'customer_id' => Yii::t('app', 'Customer ID'),
      'docnr' => Yii::t('app', 'Docnr'),
      'warehouse_id' => Yii::t('app', 'Warehouse ID'),
      'store_id' => Yii::t('app', 'Store ID'),
      'type' => Yii::t('app', 'Type'),
      'docaxnr' => Yii::t('app', 'Docaxnr'),
      'created_by' => Yii::t('app', 'Created By'),
      'work_init' => Yii::t('app', 'Work Init'),
      'work_end' => Yii::t('app', 'Work End'),
      'orgcod' => Yii::t('app', 'Orgcod'),
      'status' => Yii::t('app', 'Status'),
      'created_at' => Yii::t('app', 'Created At'),
      'updated_at' => Yii::t('app', 'Updated At'),
    ];
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
    return $this->hasMany(DocumentPosition::className(), ['document_id' => 'id']);
  }

  /**
   * Gets query for [[LpnMasters]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getLpnMasters()
  {
    return $this->hasMany(LpnMaster::className(), ['document_id' => 'id']);
  }

  /**
   * Gets query for [[SerialMasters]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getSerialMasters()
  {
    return $this->hasMany(SerialMaster::className(), ['document_id' => 'id']);
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
    return $this->hasMany(WorkOrder::className(), ['order_id' => 'id']);
  }

  /**
   * Creates data provider instance with search query applied
   *
   * @param array $params
   *
   * @return ActiveDataProvider
   */
  public function search($params)
  {

    // $userId = Yii::$app->user->id;
    // $store = (new Query())->select('store_id')->from('store_user')->where(['user_id' => $userId]);
    
    $query = self::find();
    // $query->where(['store_id'=>$store]);
    $this->load($params);

    $query->andFilterWhere(['store_id' => $this->store_id]);
    $query->andFilterWhere(['status' => $this->status]);
    $query->andFilterWhere(['customer_id' => $this->customer_id]);
    $query->andFilterWhere(['like', 'docnr', $this->docnr]);


    if (!is_null($this->created_at) && strpos($this->created_at, ' - ') !== false) {
      list($start_date, $end_date) = explode(' - ', $this->created_at);
      $query->andFilterWhere(['between', 'created_at', $start_date, $end_date]);
    }

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'sort' => ['defaultOrder' => ['docnr' => SORT_ASC]],
      'pagination' => ['pageSize' => 20],
    ]);

    return $dataProvider;
  }


  public function searchSt15($params)
  {
    $query = self::find();
    $this->load($params);

    $query->andFilterWhere(['status' => 17]);
    $query->andFilterWhere(['store_id' => $this->store_id]);
    $query->andFilterWhere(['customer_id' => $this->customer_id]);
    $query->andFilterWhere(['like', 'docnr', $this->docnr]);
    $query->andFilterWhere(['>=', 'updated_at', $this->updated_at]);
    $query->andFilterWhere(['<=', 'updated_at', $this->updated_at]);

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'sort' => ['defaultOrder' => ['docnr' => SORT_ASC]]
    ]);
    return $dataProvider;
  }

  public function getTotalReadings()
  {
    $total =  DocumentPosition::find()->where(['document_id' => $this->id])->sum('real_amount');
    return $total ?? 0;
  }

  public function getTotalPercent()
  {
    $real_amount =  DocumentPosition::find()->where(['document_id' => $this->id])->sum('real_amount');
    $amount =  DocumentPosition::find()->where(['document_id' => $this->id])->sum('amount');
    // MODO DEMO: Evitar división por cero
    if (empty($amount) || $amount == 0) {
      return 0;
    }
    return round(($real_amount/$amount)*100, 2) ?? 0;
  }
  public function getTotalAmount()
  {
    $total =  DocumentPosition::find()->where(['document_id' => $this->id])->sum('amount');
    return $total ?? 0;
  }

  public function exportDocument($id)
  {

    $sql = "SELECT s.code AS Bodega,
    c.code AS Cliente,
  dh.docnr AS Recibo,
  i.code AS Producto,
  dh.orgcod AS CentroAlmacen,
  dp.invlin AS Linea,
  dp.rcvsts AS Estatus,
  /*sm.type_id AS Serial,
  sm.config_label_id AS CFG,*/
(SELECT description
FROM serial_type st
WHERE st.id = sm.type_id ) AS Tipo_Serial,
  sm.value AS Serial,
CASE dh.type
WHEN 1 THEN 'Recibo'
WHEN 2 THEN 'Pedido'
END AS Proceso,
u2.username AS Usuario,
sm.created_at AS Fecha,
( SELECT lpnnr
FROM lpn_master lm
WHERE lm.id = sm.lpn_id ) AS LPN_Pallet,
( SELECT lpnnr
FROM lpn_master lm
WHERE lm.id = sm.lpn_pos_id ) AS SubLPN,
( SELECT itemcnt
FROM lpn_master lm
WHERE lm.id = sm.lpn_pos_id 
 AND lm.lpnty = 2 ) AS SubLPNCount
FROM document_header dh
INNER JOIN document_position dp
  ON dh.id = dp.document_id
INNER JOIN customer c
  ON c.id = dh.customer_id
INNER JOIN store s
    ON s.id = dh.store_id
INNER JOIN item i
  ON i.id = dp.item_id
INNER JOIN serial_master sm
  ON sm.customer_id = dh.customer_id AND
     sm.document_id = dh.id AND
   sm.item_id = dp.item_id
INNER JOIN config_label cl
  ON cl.id = sm.config_label_id AND
     cl.type_id = sm.type_id
INNER JOIN [user] u2
  ON u2.id = sm.user_id
WHERE dh.id =" . $id;


    $connection = Yii::$app->getDb();
    $command = $connection->createCommand($sql);
    $result = $command->queryAll();
    return $result;

    $item = Item::find()->where(['code' => $result[0]['Producto']])->one();
    $rule_slices = SerialRules::find()->where(['item_id' => $item->id])->one();

    foreach ($result as $key => $value) {
      $result[$key]['value'] = substr($value['value'], $rule_slices->sr_start - 1, $rule_slices->sr_length);
    }
    // return $result;
  }


  public function exportPrealert($id)
  {
    $sql="select c.code,
dh.docnr,
c.name,
s.code,
s.name,
i.code,
i.name,
case subquery.pre_center
when null
then ''
else subquery.pre_center
end as pre_center,
case subquery.pre_store
when null
then ''
else subquery.pre_store
end as pre_store,
case subquery.pre_invsts
when null
then ''
else subquery.pre_invsts
end as pre_invsts,
st.description ,
sl.value,
subquery.pre_serial,
case
when sl.value = subquery.pre_serial
then 'Cruza Ok'
else 'No Cruza'
end as comp_prealerta,
sl.created_at
from serial_master sm
inner join serial_list sl
on sm.id = sl.serial_master_id
inner join customer c
on c.id = sm.customer_id
inner join serial_type st
on st.id = sl.type_id
inner join document_header dh
on sm.document_id = dh.id
inner join document_position dp
on dp.document_id = dh.id and
sm.item_id = dp.item_id
inner join store s
on s.id = dh.store_id
inner join item i
on i.id = dp.item_id
left join ( select pps.serial1 as pre_serial,
ph.order_id as sq_order,
ph.center as pre_center,
ph.store as pre_store,
pp.invsts as pre_invsts
from prealert_header ph
inner join prealert_position pp
on pp.prealert_id = ph.id
inner join prealert_position_serial pps
on pps.prealert_position_id = pp.id ) as subquery
on subquery.sq_order = dh.docnr and
subquery.pre_serial = sl.value
where dh.id =".$id;

$connection = Yii::$app->getDb();
$command = $connection->createCommand($sql);
$result = $command->queryAll();
return $result;

  }
}
