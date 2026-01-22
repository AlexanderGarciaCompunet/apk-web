<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $profile common\models\UserProfile */

$this->title = Yii::t('app', 'Info User');
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
  <div class="col-md-6">
    <div class="card card-pimary">
      <div class="card-header">
        <h3 class="card-title"><?= Yii::t('app', 'Loggin Info') ?></h3>
      </div>
      <div class="card-body">
        <?= $this->render('_form', [
          'model' => $model,
          'form' => $form
        ]) ?>
      </div>
    </div>
  </div>

  <div class="col">
    <!-- asingacion de roles -->
    <?= $this->render('/user-profile/_form', [
      'model' => $profile,
      'form' => $form
    ]) ?>
  </div>
  <div class="col-md-6">
    <!-- formulario usuario-->
    <?= $this->render('/user-profile/_form_profile', [
      'model' => $profile,
      'form' => $form
    ]) ?>

  </div>
</div>
<a href="#" class="btn btn-secondary">Cancel</a>
<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success float-right']) ?>
<?php ActiveForm::end(); ?>
