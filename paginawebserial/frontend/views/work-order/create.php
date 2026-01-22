<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkOrder */

$this->title = Yii::t('app', 'Create Work Order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
