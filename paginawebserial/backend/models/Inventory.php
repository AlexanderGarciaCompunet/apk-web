<?php
namespace backend\models;

use Yii;
use yii\web\UnauthorizedHttpException;

class Inventory extends \common\models\Inventory
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
