<?php

use common\models\base\City;

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$groups = $group_model ?? [];
?>

<div class="user-form">

  <?= $form->field($model, 'username')->textInput(['disabled' => isset($profile)]) ?>

  <?= $form->field($model, 'email')->textInput(['disabled' => isset($profile)]) ?>
  <?php if (!isset($profile)) { ?>
    <?= $form->field($model, 'password')->textInput(['hidden' => isset($profile)]) ?>
  <?php } ?>

</div>
