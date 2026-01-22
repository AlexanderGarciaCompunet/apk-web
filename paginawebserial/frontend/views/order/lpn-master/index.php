<?php

use kartik\grid\GridView;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Listado de lpns');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
  <div class="col-12">
    <?= GridView::widget([
      'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
      'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
          'label'=>'Tipo',
          'value'=>function ($model){
             $val = $model->lpnty==1 ?"Pallet" : "Caja";
             return $val;

          }
        ],
        ['attribute'=>'lpnnr',
        'label'=>'Código'
        ],
        
        [
          'attribute'=>'customerCode',
          'label'=>'Cliente',
          'value'=>'customer.code',
        ],
        [
          'attribute'=>'docnr',
          'label'=>'Pedido',
          'value'=>'document.docnr'
        ],
        ['attribute'=>'item.code',
        'label'=>'Material'
        ],

      
        [
          'label'=>'Cantidad',
          'value'=>'real_amount'
        ],

        [
          'label'=>'Creado el',
          'value'=>'created_at'
        ],


        // ['class' => 'yii\grid\ActionColumn'],
      ],
      'toolbar' => [
        'export'=>false,
        'toggleData'=>false,
      ],
      'export' => [
        'fontAwesome' => true
      ],
      'responsive' => true,
      'panel' => [
        'type' => GridView::TYPE_DANGER,
        'heading' => '<i class="fas fa-box"></i>  ' . Yii::t('app', 'List'),
        'after' => false,
      ],
    ]); ?>


  </div>
</div>
</div>
