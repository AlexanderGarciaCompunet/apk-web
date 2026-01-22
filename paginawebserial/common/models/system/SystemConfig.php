<?php

namespace common\models\system;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "SystemConfig".
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $reference
 * @property string|null $value
 * @property string|null $uri
 * @property int|null $status
 * @property string $createdAt
 * @property string $updatedAt
 */
class SystemConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['status'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['reference', 'uri'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'reference' => Yii::t('app', 'Reference'),
            'value' => Yii::t('app', 'Value'),
            'uri' => Yii::t('app', 'Uri'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }
}
