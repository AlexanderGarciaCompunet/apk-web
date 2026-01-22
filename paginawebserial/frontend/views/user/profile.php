<?php

/* @var $this yii\web\View */

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $model common\models\User */

$this->title = Yii::t('app', '{modelClass} Profile ', ['modelClass' => 'User',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Controls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['view', 'id' =>
    $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>

    <div class="row">

        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle"
                         src="../../dist/img/user4-128x128.jpg" alt="User profile picture">

                    <h3 class="profile-username text-center"><?= $model->fullName ?></h3>

                    <p class="text-muted text-center">Software Engineer</p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b></b> <a class="pull-right"></a>
                        </li>
                        <li class="list-group-item">
                            <b></b> <a class="pull-right"></a>
                        </li>
                        <li class="list-group-item">
                            <b></b> <a class="pull-right"></a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block"><b> _</b></a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">

                    <li class="active">
                        <a href="#settings" data-toggle="tab" aria-expanded="true">
                            General Settings
                        </a>
                    </li>
                    <li>
                        <a href="#password" data-toggle="tab" aria-expanded="true">
                            Change Password
                        </a>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane active" id="settings">
                        <?php $form = ActiveForm::begin(['id' => 'item-form']); ?>
                        <?= $form->field($model, 'username')->textInput() ?>

                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'email')->textInput() ?>

                        <div class="box-footer">
                            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="tab-pane" id="password">
                        <?php $form = ActiveForm::begin(['id' => 'update-form']); ?>
                        <?= $form->field($change_password, 'oldPassword')->passwordInput() ?>
                        <?= $form->field($change_password, 'newPassword')->passwordInput() ?>
                        <?= $form->field($change_password, 'retypePassword')->passwordInput() ?>
                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('rbac-admin', 'Change'), ['class' => 'btn btn-primary', 'name' => 'change-button']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>

                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>

        <!-- /.col -->
    </div>

<?php

$this->registerJs(
    '$("document").ready(function(){ 
		$("#change_pass").on("pjax:end", function() {
			$.pjax.reload({container:"#countries"});  //Reload GridView
		});
    });'
);
?>