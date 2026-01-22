<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%reference}}".
 *
 * @property int $id
 * @property string|null $description
 * @property int|null $groups
 * @property int|null $orientation
 * @property int|null $created_by
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property SerialRule[] $serialRules
 */
class Reference extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reference}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['groups', 'orientation', 'created_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'groups' => Yii::t('app', 'Groups'),
            'orientation' => Yii::t('app', 'Orientation'),
            'created_by' => Yii::t('app', 'Created By'),
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
     * Gets query for [[SerialRules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSerialRules()
    {
        return $this->hasMany(SerialRule::className(), ['reference_id' => 'id']);
    }
}
