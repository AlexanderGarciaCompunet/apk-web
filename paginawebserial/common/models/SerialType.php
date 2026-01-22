<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%serial_type}}".
 *
 * @property int $id
 * @property string|null $description
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property SerialList[] $serialLists
 */
class SerialType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%serial_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by'], 'integer'],
            [['description'], 'string', 'max' => 25],
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
            'description' => Yii::t('app', 'Description'),
            'created_by' => Yii::t('app', 'Created By'),
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
     * Gets query for [[SerialLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSerialLists()
    {
        return $this->hasMany(SerialList::className(), ['type_id' => 'id']);
    }
}
