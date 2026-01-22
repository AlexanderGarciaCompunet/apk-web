<?php

use yii\bootstrap4\LinkPager;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('/customer/import')) {
  $this->params['newButton'] = Html::button(Yii::t('app', 'Update') . '<i class="fas fa-plus ml-2"></i> ', [
    'value' => Url::to(['/customer/import', 'id' => 1]),
    'title' => Yii::t('app', 'Importing Data'),
    'class' => 'showModalButton btn btn-danger rounded-pill',
    'size' => 'modal-lg'
  ]);
}
?>
<div class="customer-index">


  <?php Pjax::begin(['id' => 'test']); ?>
  <?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => [
      'class' => 'row'
    ],
    'summaryOptions' => [
      'class' => 'col-md-11 d-flex justify-content-center'
    ],
    'itemOptions' => ['class' => 'col-lg-4'],
    'itemView' => '_list_item',
    'layout' => '{items}'
  ]) ?>
  <!-- si los clientes aumentan aparecera pa paginacion -->
  <div class="d-flex justify-content-center">
    <?= LinkPager::widget([
      'pagination' => $dataProvider->pagination,
    ]) ?>
  </div>

  <?php Pjax::end(); ?>
</div>