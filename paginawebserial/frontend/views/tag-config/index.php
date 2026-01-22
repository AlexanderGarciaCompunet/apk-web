<?php

use kartik\helpers\Html;
use yii\bootstrap4\LinkPager;
use yii\bootstrap\Collapse;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->registerJs('CollapseCustomWidget.js');

$this->title = "Etiquetas";

$this->params['breadcrumbs'][] = $this->title;
$this->params['newButton'] =Html::a('<div><i class="fa fa-plus fa-fw"></i> Nueva Etiqueta</div>', ['/tag-config/config'], ['class' => ' btn btn-danger rounded-pill',]);
  // Html::a('crear' . '<i class="fas fa-plus ml-2"></i> ',['/tag-config/config'],[
//   'class' => 'showModalButton btn btn-danger rounded-pill',

// ]);


/* @var $this \yii\web\View */

?>

<?= ListView::widget([
  'dataProvider' => $dataProvider,
  'itemView' => '_list_item',
  'layout' => '{items}',
  

]) ?>
<!-- si los clientes aumentan aparecera pa paginacion -->
<div class="d-flex justify-content-center">
  <?= LinkPager::widget([
    'pagination' => $dataProvider->pagination,
  ]) ?>
</div>
