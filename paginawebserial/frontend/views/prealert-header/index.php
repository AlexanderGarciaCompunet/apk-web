<?php

use common\models\SystemConfig;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap4\Modal;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Prealerts');
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('/order/import')) {
  $this->params['newButton'] = Html::button(Yii::t('app', 'Enter File') . '<i class="fas fa-plus ml-2"></i> ', [
    'id' => 'modal-btn',
    'class' => 'btn btn-danger rounded-pill',
  ]);
}
?>
<div id="container">
  <div class="card-wrapper">
    <div class="card" style="border-top-width: 18px; border-top-color: #171352; border-radius: 15px;">
      <div class="row">
        <div class="col-12">
          <?php Pjax::begin(); ?>
          <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => '',],
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              [
                'label' => Yii::t('app', 'Store'),
                'attribute' => 'store_id',
                'value' => function ($model) {
                  return $model->customer->name;
                },
              ],
              [
                'label' => Yii::t('app', 'Customer'),
                'attribute' => 'store_id',
                'value' => function ($model) {
                  return $model->customer->name;
                },
              ],
              'center',
              'store',
              'order_id',
              'status',
              [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                  'update' => function ($url, $model, $key) {
                    $target = '_self';
                    return Html::a(
                      '<span class="fas fa-user-plus"></span> ',
                      $url
                      //  ['class' => 'btn btn-outline-primary btn-sm']
                    );
                  },
                ]
              ],
            ],
            'toolbar' => [
              '{export}',
            ],
            'export' => [
              'fontAwesome' => true
            ],
            'striped' => true,
            'resizableColumns' => true,
            'responsiveWrap' => true,
            'responsive' => true,
            'floatOverflowContainer' => true,
            'panel' => [
              'after' => false,
            ],
          ]); ?>

          <?php Pjax::end(); ?>
        </div>

      </div>
    </div>
  </div>
</div>
<?php
Modal::begin([
  'toggleButton' => false,
  'title' => '<h1>Ingrese archivo de Prealerta</h1>',
  'id' => 'modal-opened',
  'size' => 'modal-lg',
  'options' => ['style' => ['top' => '10%']],
]);
?>
<?php
$form = ActiveForm::begin([
  'action' => ['prealert-header/upload'],
  'method' => 'post',
  'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<?=
$form->field($model, 'prealert_text')->widget(FileInput::classname(), [
  'options' => ['accept' => 'text/*'],


]);
?>
<div class="form-group">
  <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
</div>
<?php
ActiveForm::end();
?>
<?php
Modal::end();
?>
<?php
$this->registerJs(
  <<<JS
      $('#modal-btn').on('click', function (event) {
        $('#modal-opened').modal('show');
      });
    JS
);
?>
