<?php

namespace console\models;

use common\models\Customer;
use common\models\Store;
use common\models\DocumentHeader;
use common\models\DocumentPosition;
use common\models\Item;
use yii\helpers\ArrayHelper;
use Yii;

class Wms
{
  public function __contruct(String $db)
  {
  }

  public function getCustomers($db = 'dlx')
  {
    $data = $this->runCommand(Yii::$app->params['sqlCustomers'], $db);
    // echo 'Importing Clients\n';

    foreach ($data as $row) {
      $customer = Customer::findOne(['code' => $row['CLIENT_ID']]);
      if (!$customer) {
        // echo "New client, code: {$row['CLIENT_ID']}, name: {$row['LNGDSC']}";
        $customer = new Customer();
        $customer->code = $row['CLIENT_ID'];
        $customer->name = $row['LNGDSC'];
        $customer->company_id = 1; //by default
        $customer->save();
      }
    }
  }

  public function getStores($db = 'dlx')
  {
    $data = $this->runCommand(Yii::$app->params['sqlStores'], $db);
    echo 'Importing Stores\n';

    foreach ($data as $row) {
      $store = Store::findOne(['code' => $row['WH_ID']]);
      if (!$store) {
        echo "New store, code: {$row['WH_ID']}, name: {$row['LNGDSC']}";
        $store = new Store();
        $store->code = $row['WH_ID'];
        $store->name = $row['LNGDSC'];
        $store->warehouse_id = 1; //by default
        $store->save() ?'SAVED'  : 'ERROR';
      }
    }
  }

  public function getSerialized($db = 'dlx')
  {
    $sqlQuery = 'sqlSerialized';
    $date = 99999;
    $data = $this->runCommandDate(Yii::$app->params['sqlSerialized'], $date, $db);
    echo 'Importing Serialized\n';
    foreach ($data as $row) {
      $item = Item::findOne(['code' => $row['PRTNUM']]);
      if (!$item) {
        $item = new Item();
        $item->code = $row['PRTNUM'];
        $item->name = $row['LNGDSC'];
        $item->netweigth = (float)(str_replace(',', '.', $row['NETWGT']));
        $item->unitnet = $row['UOMCOD'] ?? '';
        $item->grweigth = (float)(str_replace(',', '.', $row['GRSWGT']));
        $item->unitgrw = $row['UOMCOD'];
        $customerObject = Customer::findOne(['code' => $row['PRT_CLIENT_ID']]);
        $item->customer_id = $customerObject->id;
        $item->company_id = 1; //by default
        $item->status = 11; //Material nuevo sincronizado queda en estado OFF
        echo $item->save() ? ' SAVED' : 'ERROR';
        $item->save();
      }
    }
  }

  public function getNotSerialized($db = 'dlx')
  {
    $data = $this->runCommand(Yii::$app->params['sqlNotSerialized'], $db);
    echo 'Importing Not Serialized\n';

    foreach ($data as $row) {
      $item = Item::findOne(['code' => $row['PRTNUM']]);
      if (!$item) {
        echo "New store, code: {$row['WH_ID']}, name: {$row['LNGDSC']}";
        $store = new Store();
        $item->code = $row['PRTNUM'];
        $item->name = $row['LNGDSC'];
        $customerObject = Customer::findOne(['code' => $row['PRT_CLIENT_ID']]);
        $item->customer_id = $customerObject->id;
        $item->company_id = 1; //by default
        $item->status = 11; //Material nuevo sincronizado queda en estado OFF
        $item->grweigth = $row['GRSWGT'] ?? 0;
        $item->neteigth = $row['NETWGT'];
        echo $store->save() ? ' SAVED' : 'ERROR';
      }
    }
  }

  public function getOrdered($db = 'dlx')
  {
    $data = $this->runCommand(Yii::$app->params['sqlOrdered'], $db);
    echo 'Importing Received\n';

    foreach ($data as $row) {
      $documentHeader = DocumentHeader::findOne(['docnr' => $row['PRTNUM']]);
      var_dump($data);
      // die();

      if (!$documentHeader) {
        echo "New order, order: {$row['INVNUM']}, referece: {$row['PRTNUM']}, client: {$row['CLIENT_ID']}, warehouse: {$row['WH_ID'] }";
        $documentHeader = new DocumentHeader();
        $customerObject = Customer::findOne(['code' => $row['CLIENT_ID']]);
        $documentHeader->customer_id = $customerObject->id;
        $documentHeader->docnr = $row['INVNUM'];
        $documentHeader->warehouse_id = 1; //by default
        $storeObject = Store::findOne(['code' => $row['WH_ID']]);
        $documentHeader->store_id = $storeObject->id;
        echo $documentHeader->save() ? ' SAVED' : 'ERROR';



      }
    }
  }

  public function getReceived($db = 'dlx')
  {
   
    echo "Loading Customers\n";
    $customers = Customer::find()->select(['id', 'code'])->all();
    $customers = ArrayHelper::map($customers, 'code', 'id');
    echo "Loading stores\n";
    $stores = Store::find()->select(['id', 'code'])->all();
    $stores = ArrayHelper::map($stores, 'code', 'id');
    echo "Loading DocumentHeaders\n";
    $documents = DocumentHeader::find()->select(['id', 'docnr'])->all();
    $documents = ArrayHelper::map($documents, 'docnr', 'id');
    echo "Loading Items\n";
    $items = Item::find()->select(['id', 'code', 'status'])->all();
    $items = ArrayHelper::map($items, 'code', 'id');
    echo "Importing Received\n";

    $date = 99999;
    $data = $this->runCommandDate(Yii::$app->params['sqlOrdered'], $date, $db);

    echo "Analyzing\n";
    foreach ($data as $row) {
      if (isset($customers[$row['CLIENT_ID']]) && isset($stores[$row['WH_ID']])) {
        if (!isset($documents[$row['INVNUM']])) {
          $documentHeader = new DocumentHeader();
          $documentHeader->docnr = $row['INVNUM'];
          $documentHeader->orgcod = $row['ORGCOD'];
          $documentHeader->type = 1;
          $documentHeader->warehouse_id = 1; //by default
          $documentHeader->store_id = $stores[$row['WH_ID']]; //by default
          $documentHeader->customer_id = $customers[$row['CLIENT_ID']];
          $documentHeader->status = 11;
          $documentHeader->save()? 'save' :'errors';

          if ($documentHeader->save()) {
            $documents[$row['INVNUM']] = $documentHeader->id;
          }
          //TODO si existe y el status = ? cambia a ? y actualiza ?
        }

        if (isset($items[$row['PRTNUM']])) {
          $documentPosition = DocumentPosition::findOne(['document_id' => $documents[$row['INVNUM']], 'item_id' => $items[$row['PRTNUM']], 'rcvsts' => $row['RCVSTS']]);
          if (!$documentPosition) {
            $documentPosition = new DocumentPosition();
            $documentPosition->document_id =  $documents[$row['INVNUM']];
            $documentPosition->item_id =  $items[$row['PRTNUM']];
            $documentPosition->amount =  $row['EXPQTY'];
            $documentPosition->rcvsts =  $row['RCVSTS'];
            $documentPosition->invlin =  $row['INVLIN'];
            $itemObject = Item::findOne(['code' => $row['PRTNUM']]);
            $documentPosition->unit =  $itemObject->unitnet;
            $documentPosition->customer_id = $customers[$row['CLIENT_ID']];
            $itemObject = Item::findOne(['id' => $documentPosition->item_id]);
            // if ($itemObject->status == 10) {
            //   $documentObject = DocumentHeader::findOne(['docnr' => $row['INVNUM']]);
            //   $documentObject->status = 16; //TODO estado 16 bloqueado 
            //   $documentObject->save();
            //   $documentPositions = DocumentPosition::findAll(['document_id' => $documentObject->id]);
            //   // foreach ($documentObject as $position) {
            //   //   $position->status = 16;
            //   //   $position->save();
            //   // }
            // }
            if (!$itemObject) { //Valida que el material no existe aún para mandar a bloqueo por config
              $documentPosition->status = 16;
              $documentHeader->status = 16; //TODO estado 16 bloqueado 
              $documentHeader->save();
            }else{
              if($itemObject->status == 11){ //Valida que el estado del material sea "OFF" status=11 para mandar a bloqueo por config
                $documentPosition->status = 16;
                $documentHeader->status = 16; //TODO estado 16 bloqueado 
                $documentHeader->save();
              }else{
                $documentPosition->status = 12;
              }
            }
            $documentPosition->save();
          }
        }
      }
    }
    $documentHeaders = DocumentHeader::find()->all();
    foreach ($documentHeaders as $document) {
      $documentPositions = DocumentPosition::findAll(
        ['document_id' => $document->id]
      );
      if (count($documentPositions) == 0) {
        $document->status =  13;
        $document->save();
      } else {
        if ($document->status == 11) {
          $document->status =  12;
          //foreach ($documentPositions as $position) {
            //$position->status = 12;
            //$position->save();
          //}
          $document->save();
        }
      }
    }
    return "Finish";
  }

  public function getOrdered2($db = 'dlx')
  {
    $dlx = Yii::$app->dlx;
    $sql = str_replace('{database}', 'WMSDEV', Yii::$app->params['sqlOrdered']);
    $customers = $dlx->createCommand($sql)->queryAll();
    return $customers;
  }

  public function getReceived2()
  {
    $dlx = Yii::$app->dlx;
    $sql = str_replace('{database}', 'WMSDEV', Yii::$app->params['sqlReceived']);
    $customers = $dlx->createCommand($sql)->queryAll();
    return $customers;
  }

  public function getVersion()
  {
    $dlx = Yii::$app->dlx;
    $sql = str_replace('{database}', 'WMSDEV', Yii::$app->params['sqlVersion']);
    $customers = $dlx->createCommand($sql)->queryAll();
    return $customers;
  }
// dlx hace referencia a blue yonder
  private function runCommand($sql, $dataBase = 'dlx')
  {
    $db = Yii::$app->dlx;
    $table = 'DEVELOP';
    $getAllCusotmer=Customer::find()->select('code')->where(['status'=>20])->asArray()->all();

    // $data = implode(',' , $getAllCusotmer);

    $customers=[];

    foreach ($getAllCusotmer as  $value) {
      $customers[]=$value['code'];
    }
    
    
    $sql = str_replace('{database}', $table, $sql);
    $sql = str_replace('{clients}',"'". implode("','" , $customers)."'", $sql);
    // var_dump($sql);die();
    return $db->createCommand($sql)->queryAll();
  }
  private function runCommandDate($sql, $date, $dataBase = 'dlx')
  {
    $db = Yii::$app->dlx;
    $table = 'DEVELOP';

    $getAllCusotmer=Customer::find()->select('code')->where(['status'=>20])->asArray()->all();

    // $data = implode(',' , $getAllCusotmer);

    $customers=[];

    foreach ($getAllCusotmer as  $value) {
      $customers[]=$value['code'];
    }
    
  
    $date = strval($date);
    $sql = str_replace('{database}', $table, $sql);
    $sql = str_replace('{date}', $date, $sql);
    $sql = str_replace('{clients}',"'". implode("','" , $customers)."'", $sql);
   
    return $db->createCommand($sql)->queryAll();
  }

  public function saveFile($result, $file)
  {
    // Abrir el archivo, creándolo si no existe:
    $archivo[] = fopen($file, "w+b");

    if ($archivo == false) {
      echo "Error al crear el archivo";
    } else {
      // Escribir en el archivo:
      foreach ($result as $row) {
        foreach ($row as $col) {
          if ($col !=NULL) {
            # code...
            fwrite($archivo[0], "{$col}|");
          }else{
            fwrite($archivo[0], "0|");
          }
        }
        fwrite($archivo[0], "\n");
      }
      // Fuerza a que se escriban los datos pendientes en el buffer:
      fflush($archivo[0]);
    }
    // Cerrar el archivo:
    fclose($archivo[0]);
  }
}

