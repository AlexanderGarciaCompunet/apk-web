<?php
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Center */
//TODO implementacion realizada para muestra
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12 card">
        <?= $form->field($model, 'id')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'initialPreviewAsData' => false,
                'previewFileType' => 'any',
                'showUpload' => true,
            ]
        ]); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
