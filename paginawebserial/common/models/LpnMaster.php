<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "{{%lpn_master}}".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $lpnnr
 * @property int|null $lpnsup
 * @property int|null $lpnty
 * @property int|null $itemcnt
 * @property int|null $item_id
 * @property int|null $document_id
 * @property int|null $user_id
 * @property int|null $real_amount
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $customer
 * @property DocumentHeader $document
 * @property Item $item
 * @property LpnPosition[] $lpnPositions
 * @property User $user
 */
class LpnMaster extends \yii\db\ActiveRecord
{
    public $docnr;
    public $customerCode;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lpn_master}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'lpnsup', 'lpnty', 'itemcnt', 'item_id', 'document_id', 'user_id', 'real_amount', 'status'], 'integer'],
            [['created_at', 'updated_at', 'docnr', 'customerCode'], 'safe'],
            [['lpnnr'], 'string', 'max' => 18],
            [['lpnnr'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentHeader::className(), 'targetAttribute' => ['document_id' => 'id']],
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
            'lpnnr' => Yii::t('app', 'Lpnnr'),
            'lpnsup' => Yii::t('app', 'Lpnsup'),
            'lpnty' => Yii::t('app', 'Lpnty'),
            'itemcnt' => Yii::t('app', 'Itemcnt'),
            'item_id' => Yii::t('app', 'Item ID'),
            'document_id' => Yii::t('app', 'Document ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'real_amount' => Yii::t('app', 'Real Amount'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
        return $this->hasMany(LpnPosition::className(), ['lpnnr' => 'id']);
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

    public function searchByOrder($params)
    {
        $query = self::find();

        $query->joinWith(['item']);
        $query->joinWith(['document']);
        $query->joinWith(['customer']);


        $this->load($params);

    $query->andFilterWhere(['item_id' => $this->item_id]);
    $query->andFilterWhere(['lpnnr' => $this->lpnnr]);
    $query->andFilterWhere(['like','document_header.docnr' , $this->docnr]);
    $query->andFilterWhere(['like','customer.code' , $this->customerCode]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
              'pageSize' =>20,
            ],
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
          ]);
      
          return $dataProvider;
    }
}
