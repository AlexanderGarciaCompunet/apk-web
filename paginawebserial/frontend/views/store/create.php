<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = Yii::t('app', 'Create Store');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Stores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-create">
  <?= $this->render('_form', [
    'model' => $model,
  ]) ?>
</div>
