<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%prealert_position_serial}}".
 *
 * @property int $id
 * @property int|null $prealert_position_id
 * @property string|null $serial1
 * @property string|null $serial2
 * @property string|null $serial3
 * @property int|null $created_by
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PrealertPosition $prealert_position
 * @property Item $item
 */
class PrealertPositionSerial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%prealert_position_serial}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prealert_position_id', 'created_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['serial1', 'serial2','serial3'], 'string', 'max' => 255],
            [['prealert_position_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrealertPosition::className(), 'targetAttribute' => ['prealert_position_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'prealert_position_id' => Yii::t('app', 'Prealert Position'),
            'serial1' => Yii::t('app', 'serial1'),
            'serial2' => Yii::t('app', 'serial1'),
            'serial3' => Yii::t('app', 'serial1'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[PrealertPosition]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrealertPosition()
    {
        return $this->hasOne(PrealertPosition::className(), ['id' => 'prealert_position_id']);
    }

}
