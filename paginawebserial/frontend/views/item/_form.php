<?php

use kartik\checkbox\CheckboxX;
/* @var $this yii\web\View */
/* @var $model common\models\Item */

use yii\helpers\Html;
?>

<div class="card h-100 ">
  <div class="card-body">
    <div class="card-text">
      <div class="row justify-content-center align-self-center d-flex mb-4">
        <div class="col-md-5">
          <div class="card-text">
            Información del Material
          </div>

        </div>
        <div class="col-md-4">
          <?= $form->field($model, 'check')->widget(CheckboxX::classname(), [
            'disabled' => true,
          ])->label("serializado"); ?>
        </div>

        <div class="col d-flex">
          <?= Html::button(
            "OFF",
            [
              'class' => $model->status == 11 ? "btn btn-danger" : "btn btn-light",
              'onclick' => "window.location.href = '" . \Yii::$app->urlManager->createUrl(['/item/change-status', 'id' => $model->id, 'value' => 11]) . "';",
              'title' => Yii::t('app', 'Cambiar estado INACTIVO'),
            ]
          ) ?>

          <?= Html::button(
            "ON",
            [
              'class' => $model->status == 10 ? "btn btn-success" : "btn btn-light",
              'onclick' => "window.location.href = '" . \Yii::$app->urlManager->createUrl(['/item/change-status', 'id' => $model->id, 'value' => 10]) . "';",
              'title' => Yii::t('app', 'Cambiar estado ACTIVO'),
            ]
          ) ?>


        </div>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        Descripción:
      </label>
      <div class="col-sm-9">
        <?= $form->field($model, 'description')->textInput(['disabled' => true])->label(false) ?>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        Tipo:
      </label>
      <div class="col-sm-9">
        <?= $form->field($model, 'type')->textInput(['disabled' => true])->label(false) ?>

      </div>
    </div>

    <div class="card-text mb-3">
      Características
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        Peso Neto:
      </label>
      <div class="col-sm-9">
        <?= $form->field($model, 'netweigth', [
          'addon' => ['append' => ['content' => 'kg']],
        ])->textInput(['disabled' => true])->label(false) ?>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        Peso Bruto:
      </label>
      <div class="col-sm-9">
        <?= $form->field($model, 'grweigth', [
          'addon' => ['append' => ['content' => 'kg']],
        ])->textInput(['disabled' => true])->label(false) ?>
      </div>
    </div>

    <div class="card-text mb-3">
      Cliente
    </div>
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        Nombre del Cliente:
      </label>
      <div class="col-sm-9">
        <?= $form->field($model->customer, 'name')->textInput(['disabled' => true])->label(false) ?>
      </div>
    </div>

  </div>
</div>
