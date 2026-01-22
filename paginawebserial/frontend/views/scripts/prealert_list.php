<?php


use kartik\grid\GridView;
use kartik\helpers\Html;

$this->title = Yii::t('app', 'Scripts');
$this->params['breadcrumbs'][] = $this->title;



/* @var $this \yii\web\View */
?>

<div class="container">

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
     
      'docnr',


      ['label' => 'Almacén', 'value' => 'store.name'],
      ['label' => 'Bodega', 'value' => 'warehouse.name'],
      [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update}',
        'buttons' => [
          'update' => function ($url, $model, $key) {
            return Html::a(
              '<span class="fas fa-file-download"></span> ',
              ['export-prealert', 'id' => $model->id,],
              [
                'class' => 'btn btn-primary',
                'data-pjax' => 0,
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'Descargar',

              ]
              //  ['class' => 'btn btn-outline-primary btn-sm']
            );
          },
        ]
      ],
    ],
    'toolbar' =>  [
      '{toggleData}'
    ],
    'toggleDataContainer' => ['class' => 'btn-group-sm'],
    'export' => [
      'fontAwesome' => true
    ],
    'striped' => true,
    'resizableColumns' => true,
    'responsiveWrap' => true,
    'responsive' => true,
    'floatOverflowContainer' => true,
    'panel' => [
      'type' => GridView::TYPE_DANGER,
      'heading' => '<i class="fas fa-file"></i> ',
      'before' => Html::a("Atras", ['/scripts/index'], $options = ['class' => 'btn btn-outline-danger']),
    ],
  ]); ?>
</div>
