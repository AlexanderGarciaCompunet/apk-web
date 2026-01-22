<?php

namespace common\models\base;

use common\models\User;
use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $lastname
 * @property string|null $document
 * @property string|null $thumbnail
 * @property string|null $phone
 * @property string|null $otherInfo
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'lastname'], 'required'],
            [['user_id'], 'integer'],
            [['thumbnail'], 'string'],
            ['thumbnail', 'image', 'minWidth' => 120, 'maxWidth' => 120,'minHeight' => 120, 'maxHeight' => 120],
            [['name', 'lastname'], 'string', 'max' => 255],
            [['document', 'phone'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['thumbnail'], 'file', 'extensions' => 'png, jpg'],
            [['thumbnail'], 'file', 'maxSize' => '1000000'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'lastname' => Yii::t('app', 'Last Name'),
            'document' => Yii::t('app', 'Document'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'phone' => Yii::t('app', 'Phone'),
            // 'otherInfo' => 'Other Info',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('userProfiles');
    }

    public function getFullName()
    {
        return ucwords(strtolower($this->name . " " . $this->lastname));
    }

}
