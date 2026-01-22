<?php

use yii\bootstrap4\LinkPager;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Stores');
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('/order/import')) {
  $this->params['newButton'] = Html::button(Yii::t('app', 'Update') . '<i class="fas fa-plus ml-2"></i> ', [
    'value' => Url::to(['store/import']),
    'title' => Yii::t('app', 'Importing Data'),
    'class' => 'showModalButton btn btn-danger rounded-pill',
    'size' => 'modal-lg'
  ]);
}
?>
<div class="store-index">
  <?php Pjax::begin(); ?>

  <?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => [
      'class' => 'row'
    ],
    'summaryOptions' => [
      'class' => 'col-md-12 d-flex justify-content-center'
    ],
    'itemOptions' => ['class' => 'col-md-4'],
    'itemView' => '_list_item',
    'layout' => "{items}",

  ]);
  ?>
  <div class="d-flex justify-content-center">
    <?= LinkPager::widget([
      'pagination' => $dataProvider->pagination,
    ]) ?>
  </div>

  <?php Pjax::end(); ?>


</div>
