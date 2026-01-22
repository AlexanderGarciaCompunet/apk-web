<?php

namespace backend\controllers;

use backend\models\Login;
use common\models\User;
use Redis;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii;

class AuthController extends ActiveController
{
  public $modelClass = 'backend\models\User';
  public $enableCsrfValidation = false;

  /**
   * @inheritdoc
   */

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $auth = $behaviors['authenticator'];
    unset($behaviors['authenticator']);
    $behaviors['corsFilter'] = [
      'class' => Cors::className(),
      'cors' => Yii::$app->params['cors'],
    ];
    $behaviors['authenticator'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
      'except' => ['login', 'test'], /// There is no need to validate the access token method. Note the distinction between $noAclLogin
    ];

    // $behaviors['authenticator']['except'] = ['options'];
    $behaviors['authenticator'] = $auth;
    return $behaviors;
  }

  /**
   * landing
   * @return array
   * @throws \yii\base\Exception
   * @throws \yii\base\InvalidConfigException
   */
  public function actionLogin()
  {
    $model = new Login();
    $body = Yii::$app->getRequest()->getBodyParams();

    // MODO DEMO - Sin encriptación
    // Si viene con 'key', intenta desencriptar, si no, usa body directo
    if (isset($body['key']) && isset(Yii::$app->encrypter)) {
      try {
        $body = json_decode(Yii::$app->encrypter->decrypt($body['key']), true);
      } catch (\Exception $e) {
        // Si falla, asume que viene sin encriptar
        $body = $body;
      }
    }


    if ($model->load($body, '') && $model->login()) {
      $profile = $model->user->profile;
      $profile = yii\helpers\ArrayHelper::toArray($profile);
      $data = [
        'token' => $model->login(),
        'profile' => $profile,
      ];
      $key = [
        'status' => 'success',
        'data' => $data,
      ];

      // MODO DEMO - Respuesta sin encriptar
      return $key;

      // PRODUCCIÓN - Respuesta encriptada (comentado para demo)
      // $enc = Yii::$app->encrypter->encrypt(json_encode($key));
      // return ['key' => $enc];
    } else {
      $key = [
        'status' => 'error',
        'message' => $model->getErrors()
      ];

      // MODO DEMO - Respuesta sin encriptar
      return $key;

      // PRODUCCIÓN - Respuesta encriptada (comentado para demo)
      // $enc = Yii::$app->encrypter->encrypt(json_encode($key));
      // return ['key' => $enc];
    }
  }

  /**
   * landing
   * @return array
   * @throws \yii\base\Exception
   * @throws \yii\base\InvalidConfigException
   */
  public function actionLogout()
  {
    $user_id =Yii::$app->user->id;
    $user_model = User::find()->where(['id' => $user_id])->one();
    if (!empty($user_model)) {
      $user_model->access_token = NULL;
      $user_model->save(false);
    }
    Yii::$app->user->logout(false);
    return [$user_id];
  }

  /**
   * landing
   * @return array
   * @throws \yii\base\Exception
   * @throws \yii\base\InvalidConfigException
   */
  public function actionUser()
  {
    $user_id =Yii::$app->user->id;
    $user_model = User::find()->where(['id' => $user_id])->one();
    if (!empty($user_model)) {
      return [$user_id];
    }

    return ['status' => 'Error desconocido'];
  }
}
