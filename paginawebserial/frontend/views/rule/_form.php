<?php

use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\SerialRules */
/* @var $form yii\widgets\ActiveForm */
/* @var $serialTypes common\models\SerialType */
?>

<div class="card m-0 p-0">
  <div class="card-body">
    <div class="card-text mb-4">
      Configuración Serial
    </div>

    <div class="d-flex align-items-baseline mb-3">
      <div class="col-md-3 p-0">
        <div class="card-text mr-1">
          Vigencia actual:
        </div>
      </div>
      <?= DatePicker::widget([
        'model' => $model,
        'disabled' => true,
        'attribute' => 'dateto',
        'type' => DatePicker::TYPE_RANGE,
        'attribute2' => 'datefrom',
        'pluginOptions' => [
          'autoclose' => true,
          'format' => 'yyyy-mm-dd',
        ],
      ]);
      ?>

    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="card-text">
          Regla de Corte:
        </div>
      </div>

      <div class="container ml-4">
        <div class="row justify-content-sm-center">
          <div class="col-sm-4">
            <p>Posici&oacute;n Inicial:</p>
          </div>
          <div class="col-md-3">
            <?= $form->field($model, 'sr_start')->textInput(['placeholder' => 0])->label(false) ?>
          </div>
        </div>
        <div class="row justify-content-sm-center">
          <div class="col-sm-4">
            <p>Longitud:</p>
          </div>
          <div class="col-md-3">
            <?= $form->field($model, 'sr_length')->textInput(['placeholder' => 0])->label(false) ?>
          </div>
        </div>
      </div>


    </div>


   
   
  </div>
</div>
