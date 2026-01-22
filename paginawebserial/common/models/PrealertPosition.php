<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%prealert_header}}".
 *
 * @property int $id
 * @property int|null $prealert_id
 * @property int|null $item_id
 * @property int|null $lpn_id
 * @property int|null $invsts
 * @property int|null $created_by
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PrealertHeader $prealert
 * @property Item $item
 */
class PrealertPosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%prealert_position}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prealert_id', 'created_by', 'status', 'item_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['lpn_id', 'invsts'], 'string', 'max' => 255],
            [['prealert_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrealertHeader::className(), 'targetAttribute' => ['prealert_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'prealert_id' => Yii::t('app', 'Prealert'),
            'item_id' => Yii::t('app', 'Item ID'),
            'lpn_id' => Yii::t('app', 'Lpn ID'),
            'invsts' => Yii::t('app', 'Invsts'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[PrealertHeader]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrealert()
    {
        return $this->hasOne(PrealertHeader::className(), ['id' => 'prealert_id']);
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

    public function getTotalSerials()
    {
        $model= PrealertPositionSerial::find()->where(['prealert_position_id' => $this->id])->count();
        return $model;
    }

}
