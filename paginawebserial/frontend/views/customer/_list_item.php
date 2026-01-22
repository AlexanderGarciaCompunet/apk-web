<?php

use yii\helpers\Html;

/* @var $model yii\data\ActiveDataProvider */

use \frontend\assets\CardAsset;

CardAsset::register($this);

?>
<div class="card">
  <h1 class="card-title">Cliente - <?= Html::encode($model->name) ?></h1>
  <h1 class="card-title"> <?= Html::encode($model->code) ?></h1>
  <h1 class="name" id="item"> Fecha de Registro : <span class="detail"><?= Html::encode($model->created_at) ?> </span> </h1>
  <!-- <h1 class="name" id="item"> Descripción : <span class="detail"><?= Html::encode($model->description) ?> </span> </h1> -->
</div>
