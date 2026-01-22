<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%document_position}}".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $document_id
 * @property int|null $posnr
 * @property int|null $item_id
 * @property float|null $amount
 * @property string|null $unit
 * @property int|null $user_id
 * @property string|null $invlin
 * @property string|null $rcvsts
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 * @property float|null $real_amount
 * @property float|null $pivot_amount
 *
 * @property Customer $customer
 * @property DocumentHeader $document
 * @property Item $item
 * @property User $user
 */
class DocumentPosition extends \yii\db\ActiveRecord
{

  public $prealert_text_file;
  private $path;
  public $imageFile;

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return '{{%document_position}}';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['customer_id', 'document_id', 'posnr', 'item_id', 'user_id', 'status'], 'integer'],
      [['amount', 'real_amount', 'pivot_amount'], 'number'],
      [['created_at', 'updated_at'], 'safe'],
      [['invlin', 'rcvsts', 'unit'], 'string', 'max' => 10],
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
      'document_id' => Yii::t('app', 'Document ID'),
      'posnr' => Yii::t('app', 'Posnr'),
      'item_id' => Yii::t('app', 'Item ID'),
      'amount' => Yii::t('app', 'Amount'),
      'unit' => Yii::t('app', 'Unit'),
      'user_id' => Yii::t('app', 'User ID'),
      'invlin' => Yii::t('app', 'Invlin'),
      'rcvsts' => Yii::t('app', 'Rcvsts'),
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
   * Gets query for [[User]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
  }

  public function upload()
  {
    if ($this->validate()) {
      $base_path = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
      $path = $this->imageFile->saveAs($base_path);
      $this->getPath($base_path);
      return $path;
    } else {
      return false;
    }
  }
  public function getPath()
  {
    $this->path = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
    return $this->path;
  }
}
