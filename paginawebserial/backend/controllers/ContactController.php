<?php
namespace backend\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ContactController extends ActiveController
{
    public $modelClass = 'backend\models\Contact';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticatior'] = [
            'class'=> HttpBearerAuth:: className (), // Implementing access token authentication
            'except'=> ['login'], /// There is no need to validate the access token method. Note the distinction between $noAclLogin
        ];
        return $behaviors;
    }

}
