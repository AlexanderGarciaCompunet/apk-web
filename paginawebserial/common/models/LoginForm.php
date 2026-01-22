<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    Const EXPIRE_TIME = 604800;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            // MODO DEMO: Aceptar cualquier contraseña si el usuario existe
            if (!$user) {
                $this->addError($attribute, 'Usuario no encontrado.');
            }
            // No validamos la contraseña - cualquier password es válido para demo
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        // buscar listado de los usuarios
        
        if ($this->validate()) {
            $this->getUser();
            $this->_user->access_token = Yii::$app->security->generateRandomString();
            $this->_user->expire_at = time() + static::EXPIRE_TIME;
            $this->_user->save();
            return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
           // $this->_user = \Edvlerblog\Adldap2\model\UserDbLdap::findByUsername($this->username);
        }

        return $this->_user;
    }
}
