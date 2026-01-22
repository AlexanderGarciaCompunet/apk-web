<?php

use kartik\form\ActiveForm;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */
/* @var $id  integer  */

use \frontend\assets\InputsAsset;
use \frontend\assets\FontsAsset;

InputsAsset::register($this);
FontsAsset::register($this);

$this->title = 'Inicio de Sesión';

?>
<div class="login-box">
  <div class="card">
    <section class="container">
      <div class="left-half">
        <article>
          <h1 class="form-title">INICIO DE SESIÓN</h1>

          <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

          <div class="form-group-login">
            <?= $form
              ->field($model, 'username', [
                'template' => '<div class="input-wrapper">{input}<span class="input-icon"><i class="fas fa-user"></i></span></div>{error}',
              ])
              ->label(false)
              ->textInput(['placeholder' => 'Nombre de usuario', 'class' => 'form-control login-input'])
            ?>
          </div>

          <div class="form-group-login">
            <?= $form
              ->field($model, 'password', [
                'template' => '<div class="input-wrapper">{input}<span class="input-icon"><i class="fas fa-lock"></i></span></div>{error}',
              ])
              ->label(false)
              ->passwordInput(['placeholder' => 'Contraseña', 'class' => 'form-control login-input'])
            ?>
          </div>

          <div class="button-container">
            <?= Html::submitButton('Ingresar', ['class' => 'btn btn-login', 'name' => 'login-button']) ?>
          </div>

          <div class="help-link-container">
            <a data-toggle="modal" data-target="#help_modal" class="help-link" href="#">¿Necesitas ayuda?</a>
          </div>

          <?php ActiveForm::end(); ?>
        </article>
      </div>
      <div class="right-half">
        <article>
          <p class="title-description">LÍDERES EN<br />INTEGRACIÓN DE<br />TECNOLOGÍA INFORMÁTICA</p>
          <p class="title-content">
            Soluciones Empresariales<br />
            Servicios Tecnológicos<br />
            Automatización y Gestión<br />
            Servicios Digitales<br />
            Infraestructura TI<br />
            Consultoría Especializada<br />
            Soporte SAP ERP
          </p>
        </article>
        <div class="logo-container">
          <img src="<?= Yii::$app->request->baseUrl ?>/img/logo_compunet_blanco.png" alt="COMPUNET Logo" class="logo-compunet">
        </div>
      </div>
    </section>
  </div>
</div>

<?php
// Modal de Ayuda - Inicio de Sesión
Modal::begin([
  'title' => '<i class="fas fa-question-circle mr-2"></i> ¿Necesita Ayuda?',
  'titleOptions' => ['class' => 'modal-title-custom'],
  'id' => 'help_modal',
  'size' => 'modal-lg',
  'centerVertical' => true,
]); ?>

<div class="help-modal-content">
  <h5 class="help-section-title"><i class="fas fa-sign-in-alt mr-2"></i> Inicio de Sesión</h5>
  <p class="help-text">
    Para ingresar a la aplicación debe digitar su usuario y contraseña.
    Si tiene problemas para iniciar sesión, comuníquese con el administrador del sistema.
  </p>

  <hr class="help-divider">

  <h5 class="help-section-title"><i class="fas fa-bars mr-2"></i> Menú Principal</h5>
  <p class="help-text">
    Una vez ingrese podrá ver el menú principal en el panel izquierdo.
    Solo debe seleccionar la opción de la tarea que desea ejecutar dentro de la aplicación:
  </p>

  <ul class="help-list">
    <li><strong>Almacenes:</strong> Permite listar y/o actualizar los almacenes que se encuentran en el WMS.</li>
    <li><strong>Clientes:</strong> Permite listar y/o actualizar los clientes que se encuentran en el WMS.</li>
    <li><strong>Usuarios:</strong> Permite listar y modificar los usuarios del sistema. También se pueden asignar roles y almacenes a cada usuario.</li>
    <li><strong>Roles:</strong> Lista los roles creados, permite crear nuevos y modificar existentes. Los roles contienen los permisos del usuario dentro de la aplicación.</li>
    <li><strong>Materiales:</strong> Permite listar y/o actualizar los materiales del WMS. También permite configurar atributos especiales y relación de etiquetas.</li>
    <li><strong>Pedidos:</strong> Lista y actualiza desde el WMS los pedidos abiertos. Permite asignar pedidos a operarios y ver el progreso de lectura.</li>
    <li><strong>Prealerta:</strong> Permite cargar un archivo de prealerta para comparar con la información de seriales capturados.</li>
    <li><strong>Scripts:</strong> Permite ejecutar y descargar documentos con el listado de seriales capturados.</li>
    <li><strong>Información general:</strong> Muestra información de la organización y de la aplicación.</li>
  </ul>
</div>

<?php Modal::end(); ?>

<?php
$this->registerJs("
$(document).ready(function() {
    $('.help-link').on('click', function(e) {
        e.preventDefault();
        $('#help_modal').addClass('show').css('display', 'block');
        $('body').addClass('modal-open').append('<div class=\"modal-backdrop fade show\"></div>');
    });

    $('#help_modal .close, #help_modal').on('click', function(e) {
        if (e.target === this || $(e.target).hasClass('close') || $(e.target).parent().hasClass('close')) {
            $('#help_modal').removeClass('show').css('display', 'none');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }
    });

    $('#help_modal .modal-content').on('click', function(e) {
        e.stopPropagation();
    });
});
", \yii\web\View::POS_END);
?>
