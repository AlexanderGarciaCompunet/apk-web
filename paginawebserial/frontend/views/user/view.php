<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */

$this->title = $model->profile->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$gridColumn = [

  'ip',
  'userAgent',
  'host',
  'error',
  [
    'attribute' => 'date',
    'value' => function ($model) {
      return date('Y-m-d H:i:s', $model->date);
    },
  ],
];
?>

<div class="row">
  <div class="col-4">
    <?= DetailView::widget([
      'model' => $model,
      'attributes' => [
        'username',
        'email:email',
        //      'status',
        'profile.name',
        'profile.lastname',
      ],
    ]) ?>
  </div>
  <div class="col-8">
    <?= GridView::widget([
      'options' => ['class' => ''],
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
      'columns' => $gridColumn,
      // 'layout' => $layout,
      'pjax' => true,
      'floatHeader' => true,
      'floatHeaderOptions' => ['scrollingTop' => '10'],
      'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-cases']],
      'panel' => [
        'type' => GridView::TYPE_INFO,
        'heading' => '<span class="glyphicon glyphicon-book"></span>  ',
      ],
      'striped' => true,
      'resizableColumns' => true,
      'responsiveWrap' => false,
      'responsive' => true,
      'floatOverflowContainer' => true,
      'tableOptions' => ['style' => 'text-transform:uppercase'],

    ]); ?>
  </div>
</div>
