<?php

use yii\helpers\Html;

/* @var $model yii\data\ActiveDataProvider */
//se agrega el css en la view

use \frontend\assets\CardAsset;
use yii\helpers\Url;

CardAsset::register($this);
?>

<a href=<?= Url::to(['/store/view/' . $model->id]) ?> style="color: inherit;">
  <div class="card" id="card-store">
    <h1 class="card-title"><?= Html::encode($model->name) ?></h1>
    <h1 class="card-text"> Cliente : <span class="detail"><?= Html::encode(isset($model->customer) ? $model->customer->name : 'N/A') ?> </span> </h1>
  </div>
</a>
