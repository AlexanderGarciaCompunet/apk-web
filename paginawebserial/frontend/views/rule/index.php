<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Serial Rules');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serial-rules-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a(Yii::t('app', 'Create Serial Rules'), ['create'], ['class' => 'btn btn-success']) ?>
  </p>

  <?php Pjax::begin(); ?>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],

      'id',
      'customer_id',
      'item_id',
      'dateto',
      'datefrom',
      //'serialchk',
      //'sr_start',
      //'sr_length',
      //'serialty:ntext',
      //'labelty',
      //'usernr',
      //'status',
      //'created_at',
      //'updated_at',

      ['class' => 'yii\grid\ActionColumn'],
    ],
  ]); ?>

  <?php Pjax::end(); ?>

</div>
