<?php
namespace backend\models;

use Yii;
use yii\web\UnauthorizedHttpException;

class Customer extends \common\models\Customer
{
    public function fields()
    {
        return ['id', 'name'];
    }

    public function extraFields()
    {
        return [
            'profile',
        ];
    }


}
