<?php

use seisvalt\web\FontAwesomeAsset;
use yii\helpers\Html;
use yii\bootstrap4\Modal;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') {
  /**
   * Do not use this code in your template. Remove it.
   * Instead, use the code  $this->layout = '//main-login'; in your controller.
   */
  echo $this->render(
    'main-login',
    ['content' => $content]
  );
} else {

  if (class_exists('frontend\assets\AppAsset')) {
    frontend\assets\AppAsset::register($this);
    FontAwesomeAsset::register($this);
  } else {
    app\assets\AppAsset::register($this);
  }

  seisvalt\web\AdminLteAsset::register($this);

  $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
  $assets = Yii::$app->assetManager->getPublishedUrl('@web/');
?>
  <?php $this->beginPage() ?>
  <!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>">

  <head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <!-- MODO DEMO: CDN para evitar problemas de assets -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Yii2 JS (funciones básicas) -->
    <script>
      // Funciones básicas de Yii2 para formularios
      var yii = yii || {};
      yii.confirm = function(message, ok, cancel) {
        Swal.fire({
          title: 'Confirmar',
          text: message,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Sí',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed && ok) ok();
          else if (cancel) cancel();
        });
      };

      // Función kvBs4InitForm para Kartik widgets
      function kvBs4InitForm() { }
    </script>

    <?php $this->head() ?>
  </head>

  <body class="hold-transition sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

      <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset, 'assets' => $assets]
      ) ?>

      <?= $this->render(
        'left.php',
        ['directoryAsset' => $directoryAsset, 'assets' => $assets]
      )
      ?>

      <?= $this->render(
        'content.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset, 'assets' => $assets]
      ) ?>

    </div>
    <?php
    Modal::begin([
      'title' => 'your-header',
      'id' => 'your-modal',
      'size' => 'modal-md',
      'clientOptions' => ['keyboard' => false,],
      'options' => ['style' => ['top' => '5%']],
      'footerOptions' => ['style' => ['display' => 'flow-root', 'text-align' => 'center']],
    ]);
    echo '<div id="modalContent">
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; display: block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                  <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#85a2b6" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round" transform="rotate(263.924 50 50)">
                      <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
                  </circle>
              </svg>
          </div>';
    Modal::end();

    ?>

    <?php $this->endBody() ?>
  </body>

  </html>
  <?php $this->endPage() ?>
<?php } ?>
