<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SerialRules */

$this->title = Yii::t('app', 'Create Serial Rules');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Serial Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serial-rules-create">

  <h1><?= Html::encode($this->title) ?></h1>

  <?= $this->render('_form', [
    'model' => $model,
  ]) ?>

</div>
