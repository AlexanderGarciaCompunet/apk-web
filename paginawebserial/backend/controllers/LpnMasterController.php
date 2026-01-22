<?php

namespace backend\controllers;

// use model\models\LpnMaster;
use backend\models\LpnMaster;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii;

use function PHPUnit\Framework\isEmpty;

class LpnMasterController extends ActiveController
{
  public $modelClass = 'backend\models\LpnMaster';
  //120 ->recibido nuevo
  //121 ->ya existia reproceso
  //122 ->rechazado
  private  $code = [120, 121, 122];

  public function actions()
  {
    $actions = parent::actions();
    unset($actions['delete'], $actions['create'], $actions['update'], $actions['index'],  $actions['view']);

    return $actions;
  }
  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticatior'] = [
      'class' => HttpBearerAuth::className(), // Implementing access token authentication
    ];
    return $behaviors;
  }

  public function actionCreate()
  {
    $model = new LpnMaster();
    $model->user_id = Yii::$app->user->identity->id;
    $params = Yii::$app->getRequest()->getBodyParams();

    // MODO DEMO - Sin encriptación
    if (isset($params['key']) && isset(Yii::$app->encrypter)) {
      try {
        $params = json_decode(Yii::$app->encrypter->decrypt($params['key']), true);
      } catch (\Exception $e) {
        // Si falla, mantiene $params
      }
    }

    $lpnnr = Lpnmaster::find()
      ->where(['lpnnr' => $params['lpnnr']])
      ->one();

    if ($lpnnr == NULL && $model->load($params, '')) {
      $model->real_amount = 0;
      $model->status  = 11;
      $model->lpnsup = $params['lpnsup'];
      $parent = Lpnmaster::find()->where(['id' => $params['lpnsup']])->one();

      if ($parent != NULL) {
        if ($parent['itemcnt'] > $parent['real_amount']) {
          $model->save();
          return $this->generateResponse($model, 0);
        }
      } else {
        $model->save();
        return $this->generateResponse($model, 0);
      }
    } else {
      $search = [];
      $checkFullLpn = $lpnnr::find()->where(['like', 'lpnnr', $lpnnr->lpnnr . '%', false])->one();
      $checkFUllBox = $lpnnr::find()->where(['lpnsup' => $checkFullLpn->id])->all();
      $isNotFull = false;

      if ($lpnnr->lpnsup == 0 && $lpnnr->lpnty == 1) {
        if ($checkFullLpn->real_amount < $checkFullLpn->itemcnt) {
          $isNotFull = true;
        }
        foreach ($checkFUllBox as  $box) {
          if ($box->real_amount < $box->itemcnt) {
            $isNotFull = true;
          }
        }
      } else {

        if ($checkFullLpn->real_amount < $checkFullLpn->itemcnt) {
          $isNotFull = true;
        }
      }



      $retVal = ($isNotFull) ? 1 : 2;
      return $this->generateResponse($lpnnr, $retVal);
    }
  }

  private function generateResponse($model, $id_code)
  {
    $response = [
      'status' => 'error',
      'message' => Yii::t('app', 'Not Found')
    ];
    if ($model) {
      $response =  [
        'data' =>  yii\helpers\ArrayHelper::toArray($model),
        'message' => $this->code[$id_code],
      ];
    }

    // MODO DEMO - Respuesta sin encriptar
    return $response;

    // PRODUCCIÓN - Respuesta encriptada (comentado para demo)
    // $enc = Yii::$app->encrypter->encrypt(json_encode($response));
    // return ['key' => $enc];
  }
}
