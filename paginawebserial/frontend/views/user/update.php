<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $profile common\models\UserProfile */

// $this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => 'Usuario',]) . '  ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<?php $form = ActiveForm::begin(); ?>


<div class="row">

  <div class="col-md-10 d-flex justify-content-center">
    <div class="card text-center">
      <div class="card-header">
        <h3 class="card-title"><?= Yii::t('app', 'Loggin Info') ?></h3>
      </div>
      <div class="card-body">
        <?= DetailView::widget([
          'model' => $model,
          'attributes' => [
            'username',
            'email:email'
          ],
        ]) ?>
      </div>
      <div class="card-footer text-muted">
        <?= $this->render('/user-profile/_form', [
          'model' => $profile,
          'form' => $form
        ]) ?>
      </div>
    </div>

  </div>
</div>

<div class="col">
  <div class="col-12">
    <a href="<?= Yii::$app->request->referrer ?>" class="btn btn-secondary">Salir</a>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success float-right']) ?>
  </div>
</div>

<?php ActiveForm::end(); ?>







<!-- actualizacion de miniatura si tiene permisos -->
<?php if (Yii::$app->user->can('set_thumbnail')) { ?>
  <div class="row">
    <div class="col-md-6 card">
      <?= $form->field($profile, 'thumbnail')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
          'initialPreview' => [
            $profile->thumbnail
          ],
          'initialPreviewAsData' => true,
          'previewFileType' => 'any',
          'showUpload' => false,
        ]
      ]); ?>
    </div>
  </div>
<?php } ?>
