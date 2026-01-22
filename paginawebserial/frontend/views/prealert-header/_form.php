<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PrealertHeader */
/* @var $form yii\widgets\ActiveForm */
?>



    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data'] // important
        ]); 
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'text')->widget(FileInput::classname(), [
            'options' => ['accept' => 'text/*'],
            'pluginOptions' => [
                'initialPreview' => [
                    $model->text
                ],
                'initialPreviewAsData' => false ,
                'previewFileType' => 'any',
                'showUpload' => false,
                'showCaption' => true,
                'showRemove' => true,
                'showPreview' => false,
            ]
        ]); 
    ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
