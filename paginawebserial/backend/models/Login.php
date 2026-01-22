<?php
namespace backend\models;


use common\models\LoginForm;
use Yii;

class Login extends LoginForm
{
    Const EXPIRE_TIME = 604800;
    private $_user;

    public function login()
    {
        if ($this->validate()) {
            $this->_user = $this->getUser();
            if ($this->_user) {
                $access_token = $this->_user->generateAccessToken();
                $this->_user->expire_at = time() + static::EXPIRE_TIME;
                $this->_user->save();
                Yii::$app->user->login($this->_user, static::EXPIRE_TIME);
                return $access_token;
            }
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
