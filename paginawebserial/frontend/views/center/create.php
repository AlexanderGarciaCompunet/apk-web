<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Center */

$this->title = Yii::t('app', 'Create Center');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Centers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="center-create">
    <div class="card center-form card">
        <div class="card-header"></div>

        <div class="card-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
        </div>
    </div>
</div>
