<?php
namespace backend\controllers;

use backend\models\Store;
use backend\models\Warehouse;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class WarehouseController extends ActiveController
{
    public $modelClass = 'backend\models\Warehouse';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticatior'] = [
            'class'=> HttpBearerAuth:: className (), // Implementing access token authentication
        ];
        return $behaviors;
    }


    public function actionView($id){
      //http://localhost:8080/warehouse/view?id=1
     // http://localhost:8080/warehouse/view/1
      $model = Warehouse::findOne($id);

      $result = [
        'status' => 'success',
        'data' => $model
        ];

      /*
       * ['status' => success,
       * data=> [id => 1, name => bodega, stores => [
       * [id =>1 , name => almacen1],
       * [id =>2 , name => almacen2],
       * ]]
       *
       */
      return $result;
    }


}
