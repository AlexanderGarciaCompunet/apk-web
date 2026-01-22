<?php

use yii\helpers\Html;
use \frontend\assets\CardAsset;

CardAsset::register($this);

?>

<div class="card">
  <h1 class="card-title">Almacén - <?= Html::encode($model->name) ?></h1>
  <h1 class="name" id="item"> Código: <span class="detail"><?= Html::encode($model->code) ?> </span> </h1>
  <h1 class="name" id="item"> Ubicación: <span class="detail"><?= Html::encode($model->location) ?> </span> </h1>
  <h1 class="name"> Descripción: <span class="detail"> <?= Html::encode($model->description) ?></span> </h1>
</div>
