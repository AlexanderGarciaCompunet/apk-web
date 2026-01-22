<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Store */

?>

<?= Html::button('<i class="fas fa-plus"></i>' . Yii::t('app', 'Assigning Store'), [
  'value' => Url::to(['view-stores', 'id' => $model->id]),
  'title' => Yii::t('app', 'Assigning Stores'),
  'class' => 'showModalButton btn btn-success',
  'size' => 'modal-lg'
]) ?>
