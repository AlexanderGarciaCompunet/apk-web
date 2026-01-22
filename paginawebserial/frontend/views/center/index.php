<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Centers');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
  <div class="col-12">
    <div class="center-index">

      <?php Pjax::begin(); ?>

      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
          ['class' => 'yii\grid\SerialColumn'],

          //'id',
          'name',
          'description',
          'location',
          // 'company_id',
          //'client_id',
          //'other_info:ntext',
          //'status',
          //'created_at',
          //'updated_at',

          ['class' => 'yii\grid\ActionColumn'],
        ],
        'toolbar' => [
          [
            'content' =>
            Html::button('<i class="fas fa-plus"> Crear</i>', [
              'value' => Url::to(['create']),
              'title' => Yii::t('app', 'Create Center'),
              'class' => 'showModalButton btn btn-outline-success btn-sm',
            ]) . ' ' .
              Html::button('<i class="fas fa-upload"> Cargar</i>', [
                'class' => 'showModalButton btn btn-outline-primary btn-sm',
                'value' => Url::to(['upload']),
                'title' => Yii::t('app', 'Upload File'),
              ]),
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
          'type' => 'blue',
          'heading' => '<i class="fas fa-book"></i>  ' . Yii::t('app', 'List'),
          'after' => false,
        ],
      ]); ?>

      <?php Pjax::end(); ?>

    </div>
  </div>
</div>
