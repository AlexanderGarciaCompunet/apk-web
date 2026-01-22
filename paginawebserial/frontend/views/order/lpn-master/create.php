<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LpnMaster */

$this->title = Yii::t('app', 'Create Lpn Master');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lpn Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lpn-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
