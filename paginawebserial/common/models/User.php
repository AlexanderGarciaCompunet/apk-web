<?php
namespace common\models;

use common\models\UserProfile;
use common\models\system\MessageLog;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use frontend\models\UserAuthLog;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii2tech\authlog\AuthLogIdentityBehavior;
use Edvlerblog\Adldap2\model\UserDbLdap;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string|null $password write-only password
 * @property int $status
 * @property int|null $expire_at
 * @property string|null $access_token
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $organization_id
 *
 * @property MessageLog[] $messageLogs
 * @property UserProfile[] $userProfiles
 * @property Organization[] $organizations
 * @property UserProfile $profile
 */
class User extends UserDbLdap
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    public $redis;
    public $online;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'authLog' => [
                'class' => AuthLogIdentityBehavior::className(),
                'authLogRelation' => 'authLogs',
                'defaultAuthLogData' => function ($model) {
                    return [
                        'ip' => Yii::$app->request->getUserIP(),
                        'host' => @gethostbyaddr(Yii::$app->request->getUserIP()),
                        'url' => Yii::$app->request->getAbsoluteUrl(),
                        'userAgent' => Yii::$app->request->getUserAgent(),
                    ];
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // Modo DEMO: Buscar directamente en la BD sin LDAP
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        if ($this->profile === null) {
            return $this->username;
        }
        return ucwords(strtolower($this->profile->name . " " . $this->profile->lastname));
    }

    public function delete()
    {
        $this->status = 0;
        $this->save();
    }

    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['user_id' => 'id']);
    }

    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Organizations]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getOrganizations()
    {

        return $this->hasMany(Organization::className(), ['contact_id' => 'id']);
    }

    public function getAuthLogs()
    {
        return $this->hasMany(UserAuthLog::className(), ['userId' => 'id']);
    }

    /**
     * Gets query for [[Organizations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessageLogs()
    {
        return $this->hasMany(MessageLog::className(), ['user_id' => 'id']);
    }


    public function checkRefresh(){
        $refresh = $this->redis->hget("users", "refresh");
        $this->redis->hset("users", "refresh", 0);

        if ($refresh != 0 && $refresh != 1) {
            //Variable no seteada, la base de datos no esta inicializada
            $refresh = 1;
        }

        if ($refresh) {
           // echo "Refrescando Cache";
            $this->redis->del("usersList");
            $users = self::find()->where(['status' => 10])->all();

            foreach ($users as $user){
                $data = $user->getAttributes([
                    'id','username','email', 'access_token'
                ]);
                $data['name'] = $user->profile->name;
                $data['lastname'] = $user->profile->lastname;
                $data['document'] = $user->profile->document;
                $data['thumbnail'] = $user->profile->thumbnail;
                $data['address'] = $user->profile->address;
                $data['phone'] = $user->profile->phone;
                $data['signature'] = $user->profile->signature;
                $data['fullName'] = $user->fullName;
                $data['specialities'] = $user->allSpecialities;
                $data['online'] = false;
                $data['state'] = '';

                $this->redis->hset("user", $data['id'], json_encode($data));
                $this->redis->rpush("usersList", $data['id']);
            }
        }
    }

    public function endSession() {
        $this->redis = Yii::$app->redis;
        $this->checkRefresh();
        $data = json_decode($this->redis->hget("user", $this->id), true);
        $data['online'] = false;
        $data['state'] = '';
        $this->redis->hset("user", $this->id, json_encode($data));
        $elephant = new Client(new Version2X(Yii::$app->params['timesocket']));
        $elephant->initialize();
        $elephant->emit('setState', ['id' =>  $this->id, 'online' => false, 'state' => '']);
        $elephant->close();

    }

    public function getUserCache()
    {
        $this->redis = Yii::$app->redis;
        $appointment = json_decode($this->redis->hget("medicalAppointment", $this->id), true);
        $user = json_decode($this->redis->hget("user", $this->id), true);
        $user['uri'] = '';
        if ($appointment) {
            $user['uri'] = $appointment['uri'];
        }
        return $user;
    }

    public function getChat($idf = 0) {
        $channel = 'SAllM' . $idf;
        $redis = Yii::$app->redis;
        $messages = $redis->lrange($channel, 0, -1);
        $response =[];

        foreach ($messages as $msg) {
            $response[] = json_decode($msg, true);
        }

        return $response;
    }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => Yii::t('app', 'ID'),
      'username' => Yii::t('app', 'User'),
      'fullName' => Yii::t('app', 'Full Name'),
      'phone' => Yii::t('app', 'Phone'),
      'email' => Yii::t('app', 'Email'),
      'status' => Yii::t('app', 'Status'),
    ];
  }

  public function beforeSave($insert)
  {
    // Modo DEMO: No usar LDAP
    if ($insert) {
      if (empty($this->password_hash)) {
        $this->password_hash = $this->auth_key;
      }
    }
    // Llamar al beforeSave de ActiveRecord directamente, saltando UserDbLdap
    return \yii\db\ActiveRecord::beforeSave($insert);
  }


  public function afterSave($insert, $changedAttributes)
  {
    // Modo DEMO: No usar LDAP, crear perfil vacío si no existe
    if ($insert) {
      $profile = UserProfile::findOne(['user_id' => $this->id]);
      if (!$profile) {
        $profile = new UserProfile();
        $profile->name = 'Usuario';
        $profile->lastname = 'Demo';
        $profile->user_id = $this->id;
        $profile->save();
        $auth = \Yii::$app->authManager;
        $authorRole = $auth->getRole('operator');
        if ($authorRole) {
          $auth->assign($authorRole, $this->id);
        }
      }
    }
    // Llamar al afterSave de ActiveRecord directamente
    \yii\db\ActiveRecord::afterSave($insert, $changedAttributes);
  }
  
}
