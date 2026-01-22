<?php

use kartik\grid\GridView;
use kartik\helpers\Html;
use common\models\SystemConfig;
use yii\bootstrap4\Modal;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\PrealertHeader */

$this->title =  Yii::t('app', 'Prealert') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Prealerts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">
  <div class="col-8">
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
          'label' => 'Material',
          'value' => function ($model) {

            return Html::a($model->item->code . ' ' . $model->item->name, ['/item/view', 'id' => $model->item_id]);
          },
          'format' => 'raw'
        ],
        'lpn_id',
        'invsts',
        [
          'label' => Yii::t('app', 'Serials Quantity'),
          'value' => function ($quantity) {
            return $quantity->totalSerials;
          },
        ],
      ],
      'toolbar' => [
        '{export}',
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
    ]);
    ?>


    <?php Pjax::end(); ?>
  </div>
  <div class="col-4">
    <?php Pjax::begin(); ?>
    <?php Pjax::end(); ?>
  </div>
</div>
