<?php
namespace backend\models;

use Yii;
use yii\web\UnauthorizedHttpException;

class User extends \common\models\User
{
    public function fields()
    {
        return ['email'];
    }

    public function extraFields()
    {
        return [
          //  'profile',
            'profile',
        ];
    }

    public function getProfile(){
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }

    /**
     * Generate accessToken string
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateAccessToken()
    {
        $this->access_token=Yii::$app->security->generateRandomString();
        return $this->access_token;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        $user = static::find()->where(['access_token' => $token, 'status' => self::STATUS_ACTIVE])->one();
        if (!$user) {
            return false;
        }
        if ($user->expire_at < time()) {
            throw new UnauthorizedHttpException('the access - token expired ', -1);
        } else {
            return $user;
        }
    }

}
