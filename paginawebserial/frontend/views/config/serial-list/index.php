<?php

use kartik\grid\GridView;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Serial Lists');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
  <div class="col-12">



    <p>
        <?= Html::a(Yii::t('app', 'Create Serial List'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'serial_master_id',
            'type_id',
            'value',
            'status',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
      'toolbar' => [
        [
          'content' =>
            Html::a('<i class="fas fa-plus"></i>', ['create'], ['class' => 'btn btn-success']),
          'options' => ['class' => 'btn-group mr-2']
        ],
        '{export}',
        '{toggleData}',
      ],
      'export' => [
        'fontAwesome' => true
      ],
      'responsive' => true,
      'panel' => [
        'type' => GridView::TYPE_DANGER,
        'heading' => '<i class="fas fa-book"></i>  ' . Yii::t('app', 'List'),
        'after' => false,
      ],
    ]); ?>


</div>
</div>
</div>
