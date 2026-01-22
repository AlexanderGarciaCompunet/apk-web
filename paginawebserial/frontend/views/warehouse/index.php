<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap4\LinkPager;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Almacenes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="warehouse-index">
  <?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => [
      'class' => 'row'
    ],
    'summaryOptions' => [
      'class' => 'col-md-12 d-flex justify-content-center'
    ],
    'itemOptions' => ['class' => 'col-lg-4'],
    'itemView' => '_list_item',
    'layout' => "{items}",
  ]) ?>
  <div class="d-flex justify-content-center">
    <?= LinkPager::widget([
      'pagination' => $dataProvider->pagination,
    ]) ?>
  </div>
</div>
