<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "user_auth_log".
 *
 * @property int $id
 * @property int $userId
 * @property int $date
 * @property int $cookieBased
 * @property int $duration
 * @property string $error
 * @property string $ip
 * @property string $host
 * @property string $url
 * @property string $userAgent
 *
 * @property User $user
 */
class UserAuthLog extends \yii\db\ActiveRecord
{
  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'user_auth_log';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['userId', 'date', 'cookieBased', 'duration'], 'integer'],
      [['error', 'ip', 'host', 'url', 'userAgent'], 'string', 'max' => 255],
      [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'userId' => 'User ID',
      'date' => 'Date',
      'cookieBased' => 'Cookie Based',
      'duration' => 'Duration',
      'error' => 'Error',
      'ip' => 'Ip',
      'host' => 'Host',
      'url' => 'Url',
      'userAgent' => 'User Agent',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(User::className(), ['id' => 'userId']);
  }

  public function search($params)
  {
    $query = self::find()
      ->orderBy(['id' => SORT_DESC]);
    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 5,
      ],
      //     'sort' => ['defaultOrder' => ['name' => SORT_DESC]]
    ]);
    $this->load($params);
    $query->andFilterWhere([
      'userId' => $this->userId,

    ]);
    return $dataProvider;
  }
}
