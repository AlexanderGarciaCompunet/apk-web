<?php
namespace backend\controllers;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii;

class UserController extends ActiveController
{
    public $modelClass = 'backend\models\User';
    public $enableCsrfValidation = false;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['create'], $actions['update'], $actions['index'],  $actions['view']);

        return $actions;
    }

    /**
     * List of allowed domains.
     * Note: Restriction works only for AJAX (using CORS, is not secure).
     *
     * @return array List of domains, that can access to this API
     */
    public static function allowedDomains() {
        return [
            // '*',                        // star allows all domains
            'http://localhost:8090',
            'http://localhost:8080',
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => static::allowedDomains(),
                'Access-Control-Request-Methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Methods' => ['GET','POST','OPTIONS','DELETE','PUT'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Allow-Origin' => static::allowedDomains(),
                'Access-Control-Allow-Headers' =>  ['X-Requested-With', 'Content-Type', 'X-Token-Auth', 'Authorization'],
                'Access-Control-Expose-Headers' => ['*'],
            ],

        ];
        $behaviors['authenticatior'] = [
            'class'=> HttpBearerAuth:: className (), // Implementing access token authentication
         ];
        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['authenticator'] = $auth;
        return $behaviors;
    }


    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        return [
            'status' => 'success',
            'data' => $user->profile
        ];
    }

}
