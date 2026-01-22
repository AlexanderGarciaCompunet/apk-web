<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SerialList */

$this->title = Yii::t('app', 'Create Serial List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Serial Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serial-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
