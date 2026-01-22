<?php

use yii\helpers\Html;
use mdm\admin\components\RouteRule;
use mdm\admin\AutocompleteAsset;
use yii\helpers\Json;
use kartik\form\ActiveForm;
use mdm\admin\components\Configs;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$rules = Configs::authManager()->getRules();
unset($rules[RouteRule::RULE_NAME]);
$source = Json::htmlEncode(array_keys($rules));
$js = <<<JS
    $('#rule_name').autocomplete({
        source: $source,
    });
JS;
AutocompleteAsset::register($this);
$this->registerJs($js);
?>

<div class="auth-item-form">
  <?php $form = ActiveForm::begin(['id' => 'item-form', 'action' => ['/admin/role/create'], 'method' => 'post']); ?>
  <div class="row">
    <div class="col-sm-12">
      <div class="row">
        <div class="col-sm-8">
          <div class="card-text font-weight-bold">
            Crear Rol
          </div>
        </div>
        <div class="col-sm-4">
          <?php
          echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), [
            'class' => $model->isNewRecord ? 'btn  bg-danger' : 'btn bg-danger',
            'name' => 'submit-button'
          ])
          ?>
        </div>
      </div>
      <?= $form->field($model, 'name', [
        'feedbackIcon' => ['default' => 'user']
      ])->textInput(['placeholder' => Yii::t('app', 'Username'), 'maxlength' => 64, 'class' => 'form__field']); ?>


      <?= $form->field($model, 'description', [
        'feedbackIcon' => ['default' => 'pen-alt']
      ])->textInput(['placeholder' => 'Descripción', 'id' => 'rule_name', 'class' => 'form__field']); ?>


    </div>
  </div>


  <?php ActiveForm::end(); ?>
</div>
