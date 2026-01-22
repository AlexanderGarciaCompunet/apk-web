<?php

namespace console\controllers;

use common\models\DocumentHeader;
use common\models\DocumentPosition;
use common\models\Item;
use console\models\Userldap;
use yii\console\Controller;
use console\models\Wms;
use yii;


class WmsController extends Controller
{
  public function actionGetCustomers($db = 'dlx')
  {
    $wms = new Wms();
    $wms->getCustomers($db);
  }

  public function actionGetStores($db = 'dlx')
  {
    $wms = new Wms();
    $wms->getStores($db);
  }

  public function actionGetOrdered($db = 'dlx')
  {
    $wms = new Wms();
    $wms->getOrdered($db,'sqlOrdered');
  }

  public function actionGetReceived($db = 'dlx')
  {
    $wms = new Wms();
    $wms->getReceived($db);
  }

  public function actionGetSerialized($db = 'dlx')
  {
    $wms = new Wms();
    $wms->getSerialized($db, 'sqlSerialized');
  }

  public function actionGetNotSerialized($db = 'dlx')
  {
    $wms = new Wms();
    $wms->getNotSerialized($db);
  }
  public function actionGetUserLdap()
  {
    $user= new Userldap();
    $user->getUsersFromLdap();
  }
  
  public function actionExportData()
  {
    $redis = Yii::$app->redis;
    
    $stringData = $redis->rpop('exportList');
    
    while($stringData){
      $arrayData = explode(',', $stringData);
      // $base_path = "D://htdocs//cititor_server//frontend//web//uploads//Almaviva//SERMAS";
      $base_path = "C://Interfaz BY//generados//SERMAS";
      $wms = new Wms();
      $model = new  DocumentHeader();
      $data = $model->exportDocument($arrayData[0]);
      $file = $base_path . $arrayData[1]. '-' . date("YmdHis") . ".txt";
      $wms->saveFile($data,$file);
      $stringData = $redis->rpop('exportList');
    }
    
    //return Yii::$app->response->sendFile($file);

  }
}
