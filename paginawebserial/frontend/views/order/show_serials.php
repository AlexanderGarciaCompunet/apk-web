<?php

use backend\models\LpnMaster;
use common\models\SerialList;
use common\models\SerialMaster;
use common\models\SerialType;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View /
/ @var $model common\models\DocumentHeader */

$this->title = 'Listado de Seriales';

?>
<div class="document-header-update">



  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'kartik\grid\SerialColumn'],
      [
        'label' => 'Serial Leido',
        'attribute' => 'value',
        'headerOptions' => [
          'class' => 'text-center',
          'style' => 'text-align: center;',
        ],
        'value' => 'value',
        'format' => 'html'

      ],
      [
        'label' => 'Documento',
        'attribute' => 'docnr',
        'headerOptions' => [
          'class' => 'text-center',
          'style' => 'text-align: center;',
        ],
        'value' => 'docnr',
        'format' => 'html'

      ],
      [
        'label' => 'Material',
        'attribute' => 'code',
        'headerOptions' => [
          'class' => 'text-center',
          'style' => 'text-align: center;',
        ],
        'value' => 'code',
        'format' => 'html'

      ],

      [
        'label' => 'LPN Pallet',
        'attribute' => 'lpn_pallet',
        'headerOptions' => [
          'class' => 'text-center',
          'style' => 'text-align: center;',
        ],
        'value' => 'lpn_pallet',
        'format' => 'html'

      ],

      [
        'label' => 'LPN Caja',
        'attribute' => 'lpn_caja',
        'headerOptions' => [
          'class' => 'text-center',
          'style' => 'text-align: center;',
        ],
        'value' => 'lpn_caja',
        'format' => 'html'

      ],
     
      [
        'label' => 'Fecha de Lectura',
        'attribute' => 'created_at',
        'value' => 'created_at',
        'format' => 'datetime',
        'filter' => DateRangePicker::widget([
          'model' => $searchModel,
          'attribute' => 'created_at',
          'convertFormat' => true,
          'pluginOptions' => [
            'locale' => [
              'format' => 'Y-m-d'
            ],
          ],
        ]),

      ],
    ],

    'panel' => [
      'type' => GridView::TYPE_DANGER,
      'heading' => '<i class="fas fa-barcode"></i>',
      'after' => false,


    ],
  ]);
  ?>


</div>
