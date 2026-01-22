<?php

use yii\helpers\Html;
use yii\helpers\Url;


// $this->registerCssFile('..\assets\css\CollapseCustomWidget.css');

use \frontend\assets\CollapseAsset;
CollapseAsset::register($this);
$firstClass = 'classcard-header py-0 col-12 accordion custom-card ' . ($index % 2 == 0 ? '' : 'bg-dark');

?>

<div class="card col-12 p-0 custom-card">

  <div class="<?= $firstClass ?>" id="headingOne">
    <h5 class="mb-0">
      <button class="btn btn-link text-white font-weight-bold text-capitalize " data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
      <?= $tag->id ?> - <?= $tag->name ?> 
      </button>
    </h5>
  </div>

  <div id="collapseOne" class="panel collapse show custom-card" aria-labelledby="headingOne" data-parent="#accordion" style="display: none;">
    <div class="card-body d-flex flex-row py-5 px-1 col-12">
      <div class="d-flex flex-column col-7">



        <div class="d-flex flex-row my-2">
          <label class="col-4" for="description">Descripción</label>
          <?= Html::textInput('description', $tag->description,   ['disabled' => true, 'class' => ' col-7']); ?>

        </div>

        <?php

        if ($tag->config_id == 4) { ?>
          <div class="d-flex flex-row my-2">
            <label class="col-4" for="columns">Columnas</label>
            <?= Html::textInput('qtColumns', $tag->qtColumns,  ['disabled' => true, 'class' => ' col-7']); ?>
          </div>
          <div class="d-flex flex-row my-2">
            <label class="col-4" for="groups">Grupos</label>
            <?= Html::textInput('qtColumns', $tag->reference_id,  ['disabled' => true, 'class' => ' col-7']); ?>
          </div>
          <div class="d-flex flex-row my-2">
            <label class="col-4" for="orientation">Orientación</label>
            <?= Html::textInput('orientation', $tag->reference_id, ['disabled' => true, 'class' => ' col-7']); ?>
          </div>
        <?php }
        ?>
        <div class="d-flex flex-row my-2">
          <label class="col-4" for="time">Tiempo</label>
          <?= Html::textInput('time', $tag->time, ['disabled' => true, 'class' => ' col-7']); ?>
        </div>
        <div class="d-flex flex-row my-2">
          <label class="col-4" for="typeL">Tipo Lector</label>
          <?= Html::textInput('typeL', ($tag->typeCapture) == 0 ? 'Escaner' : 'Camara', ['disabled' => true, 'class' => ' col-7']); ?>
        </div>
        <div class="d-flex flex-row my-2">
          <label class="col-4" for="typeL">Serial Principal</label>
          <?= Html::textInput('typeL', ($tag->type->description), ['disabled' => true, 'class' => ' col-7']); ?>
        </div>

      </div>

      <div class="d-flex flex-column justify-content-between m-auto col-4">
        <div class="d-flex flex-row my-2">

          <?php
          if ($tag->config_id == 4) {
            switch ($tag->reference_id) {

              case 1:
                echo Html::img(
                  Url::to('@web/img/type2.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
                break;
              case 2:
                echo Html::img(
                  Url::to('@web/img/type3.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
                break;
              case 3:
                echo Html::img(
                  Url::to('@web/img/type4.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
                break;
              case 4:
                echo Html::img(
                  Url::to('@web/img/type6.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
                break;
              default:

                echo Html::img(
                  Url::to('@web/img/Barcode2.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
            }
          } else {
            switch ($tag->config_id) {

              case 1:
                echo Html::img(
                  Url::to('@web/img/QR.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
                break;
              case 2:
                echo Html::img(
                  Url::to('@web/img/Datamatrix.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
                break;
              case 3:
                echo Html::img(
                  Url::to('@web/img/PDF417.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
                break;
              case 4:
                echo Html::img(
                  Url::to('@web/img/Barcode2.png'),
                  [
                    'alt' => 'pic not found',
                    'class' =>  'image-card'
                  ]
                );
                break;
            }
          }

          ?>
        </div>
        <?= Html::a('<div>Materiales Asociados</div>', ['/tag-config/show', 'id' => $tag->id], ['class' => 'py-0 btn btn-danger rounded-pill',]); ?>
      </div>
    </div>
  </div>
</div>