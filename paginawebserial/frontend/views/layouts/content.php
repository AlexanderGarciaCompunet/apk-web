<?php
/* @var $content string */

use yii\bootstrap\Modal;


?>
<div class="content-wrapper p-md-4" style="background-color: #E6E6E6;">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
          <?php } else { ?>
            <h1 class="title-header">
              <?php
              if ($this->title !== null) {
                echo ucwords(\yii\helpers\Html::encode($this->title));
              } else {
                echo \yii\helpers\Inflector::camel2words(
                  \yii\helpers\Inflector::id2camel($this->context->module->id)
                );
                echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
              } ?>

              <div class="float-right">
                <?php
                if (isset($this->params['newButton'])) {
                  echo $this->params['newButton'];
                }
                ?>

                <?php
                if (isset($this->params['serialButton'])) {
                  echo $this->params['serialButton'];
                }
                ?>
                <?php
                if (isset($this->params['addButton'])) {
                  echo $this->params['addButton'];
                }
                ?>
              </div>
            </h1>
          <?php } ?>
        </div>
        <div class="col-md-12">
          <h1 class="subtitle-header mt-2">
            <?php
            if (isset($this->params['subtitles'])) {
              echo $this->params['subtitles'];
            }
            ?>
          </h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <?= \dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]) ?>
    <?= $content ?>
  </section>
</div>


<!-- Control Sidebar - Oculto para demo -->
<!--
<aside class="control-sidebar control-sidebar-dark">
  ... contenido removido para demo ...
</aside>
<div class='control-sidebar-bg'></div>
-->
