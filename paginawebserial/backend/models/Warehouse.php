<?php
namespace backend\models;

use Yii;
use yii\web\UnauthorizedHttpException;

class Warehouse extends \common\models\Warehouse
{
    public function fields()
    {
        return ['id', 'name'];
    }

    public function extraFields()
    {
        return [
            'stores',
        ];
    }


}
