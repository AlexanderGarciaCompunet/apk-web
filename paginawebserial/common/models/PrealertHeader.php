<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%prealert_header}}".
 *
 * @property int $id
 * @property int|null $store_id
 * @property int|null $customer_id
 * @property string|null $center
 * @property string|null $store
 * @property int|null $order_id
 * @property string|null $prealert_text
 * @property int|null $created_by
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $customer
 * @property Store $store0
 */
class PrealertHeader extends \yii\db\ActiveRecord
{
  /**
   * @var UploadedFile
   */
  public $prealert_text_file;
  private $path;

  public static function tableName()
  {
    return '{{%prealert_header}}';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['store_id', 'customer_id', 'created_by', 'status'], 'integer'],
      [['prealert_text'], 'string'],
      [['prealert_text'], 'file', 'extensions' => 'txt, csv'],
      [['prealert_text'], 'file', 'maxSize' => '1000000'],
      [['created_at', 'updated_at'], 'safe'],
      [['center', 'store', 'order_id'], 'string', 'max' => 255],
      [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
      [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => Yii::t('app', 'ID'),
      'store_id' => Yii::t('app', 'Store ID'),
      'customer_id' => Yii::t('app', 'Customer ID'),
      'center' => Yii::t('app', 'Center'),
      'store' => Yii::t('app', 'Store'),
      'order_id' => Yii::t('app', 'Order ID'),
      'created_by' => Yii::t('app', 'Created By'),
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
   * Gets query for [[Store0]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getStore0()
  {
    return $this->hasOne(Store::className(), ['id' => 'store_id']);
  }

  public function upload()
  {
    $key = Yii::$app->params['cryptKey'];
    $this->path = "uploads/{$key}/". date("Y-m-d")."/";
    if (!file_exists(Yii::$app->basePath . '/web/' . $this->path)) {
        mkdir(Yii::$app->basePath . '/web/' . $this->path, 0775, true);
    }
    if ($this->validate()) {
        if ($this->prealert_text_file) {
            $pathFile = $this->saveFile('prealert_text');
            return $pathFile;
        }
    } 
    else {
      return false;
    }
  }
  private function saveFile($type)
  {
    //Revisar para guardar en disco D
    $base_url = Yii::getAlias('@web') . '/' .  $this->path; //Direccion guardado
    $text = $type . '_file';
    $ext = $this->$text->extension;
    $name = Yii::$app->security->generateRandomString() . ".{$ext}";
    $file = Yii::$app->basePath . '/web/' . $this->path . $name;
    //Guarda el archivo en disco y lo referencia en el modelo results
    $this->$text->saveAs($file);
    $this->$type = $base_url . $name;
    return $this->path . $name;
  }
}
