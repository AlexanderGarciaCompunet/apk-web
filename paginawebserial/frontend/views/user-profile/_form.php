<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (Yii::$app->user->can('/admin/assignment/assign') && !$model->isNewRecord) { ?>
  <div class="col">
    <div class="col-md-12">
      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title"><?= Yii::t('app', 'Profile Info') ?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="card-body">
          <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'document')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        </div>
      </div>
    </div>
  </div>

<?php } ?>

<div class="row d-flex justify-content-center">
  <div class="col-md-4">
    <?= $this->render('/user-profile/_form_store', [
      'model' => $model,
    ]) ?>
  </div>

  <div class="col-md-4">
    <?= Html::button('<i class="fas fa-plus"></i>' . Yii::t('app', 'Assigning Roles Or Permissions'), [
      'value' => Url::to(['view-permission', 'id' => $model->user_id]),
      'title' => Yii::t('app', 'Assigning Roles Or Permissions'),
      'class' => 'showModalButton btn btn-success',
      'size' => 'modal-lg'
    ]) ?>
  </div>

</div>
<?php
?>
