<?php

use common\models\SerialType;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use \frontend\assets\ConfigItemAsset;
ConfigItemAsset::register($this);

$model->epsilon = 100;
$model->time = 5 ;


$this->title = "Nueva Etiqueta";

$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>



<div class="column justify-content-around p-0 m-0">



  <div class="col-12">
    <div class="row">
      <div class="col">

        <div class="card">
          <div class="card-body">

            <div class="card-text mb-4">
              Estructura Serial
            </div>

            <div class="form-group row">
              <label class="col-md-3">Serial 1:</label>
              <div class="col-md-9">
                <?= $form->field($model, 'serialList[1]')->widget(Select2::classname(), [
                  'data' => ArrayHelper::map($serialTypes, 'id', 'description'),
                  'language' => 'es',
                  'options' => [
                    'placeholder' => 'Seleccione un Tipo ...',
                  ],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ])->label(false);
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3">Serial 2:</label>
              <div class="col-md-9">
                <?= $form->field($model, 'serialList[2]')->widget(Select2::classname(), [
                  'data' => ArrayHelper::map($serialTypes, 'id', 'description'),
                  'language' => 'es',
                  'options' => [
                    'placeholder' => 'Seleccione un Tipo ...',
                  ],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ])->label(false);
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3">Serial 3:</label>
              <div class="col-md-9">
                <?= $form->field($model, 'serialList[3]')->widget(Select2::classname(), [
                  'data' => ArrayHelper::map($serialTypes, 'id', 'description'),
                  'language' => 'es',
                  'options' => [
                    'placeholder' => 'Seleccione un Tipo ...',
                  ],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ])->label(false);
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3">Serial 4:</label>
              <div class="col-md-9">
                <?= $form->field($model, 'serialList[4]')->widget(Select2::classname(), [
                  'data' => ArrayHelper::map($serialTypes, 'id', 'description'),
                  'language' => 'es',
                  'options' => [
                    'placeholder' => 'Seleccione un Tipo ...',
                  ],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ])->label(false);
                ?>
              </div>
            </div>

          </div>

        </div>
      </div>


      <div class="col">

        <div class="card">
          <div class="card-body">

            <div class="card-text mb-4">
              Datos:
            </div>

            <div class="form-group row">
              <label class="col-md-3">Nombre:</label>
              <div class="col-md-9">
                <?= $form->field($model, 'name')->textInput()->input('text', ['placeholder' => "Nombre"])->label(false) ?>

              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3">Descripción:</label>
              <div class="col-md-9">
                <?= $form->field($model, 'description')->textarea(['style' => 'resize:none; word-wrap:break-word; height:75px;',
                ])->label(false) ?>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3">Serial Principal:</label>
              <div class="col-md-9">
              <?= $form->field($model, 'typeAux')->widget(Select2::classname(), [
                  'data' => [1=>'Serial 1 ',2 =>'Serial 2 ',3=>'Serial 3 ',4=>'Serial 4 '],
                  'language' => 'es',
                  'options' => [
                    'placeholder' => 'Seleccione un Tipo ...',
                  ],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ])->label(false);
                ?>
              </div>
            </div>

          </div>

        </div>


      </div>
    </div>

  </div>



  <div class="card col-12">
    <div class="card-body">

      <!-- Tipos de códigos de barras -->
      <div class="container parent">

        <div class="row">
          <div class='col text-center'>
            <label>
              <input type="radio" name="label_types" value="1" type="button" onClick="hiddenDiv('divMsg')">
              <img src="<?= Yii::getAlias('@web') ?>/img/QR.png" alt="QR Code">
              <div class="card-body ">
                <p class="card-text">QR</p>
              </div>
            </label>
          </div>

          <div class='col text-center'>
            <label>
              <input type="radio" name="label_types" value="2" type="button" onClick="hiddenDiv('divMsg')">
              <img src="<?= Yii::getAlias('@web') ?>/img/Datamatrix.png" alt="DataMatrix">
              <div class="card-body ">
                <p class="card-text">DataMatrix</p>
              </div>
            </label>
          </div>

          <div class='col text-center'>
            <label>
              <input type="radio" name="label_types" value="3" type="button" onClick="hiddenDiv('divMsg')">
              <img src="<?= Yii::getAlias('@web') ?>/img/PDF417.png" alt="PDF417">
              <div class="card-body ">
                <p class="card-text">PDF417</p>
              </div>
            </label>
          </div>

          <div class='col text-center'>
            <label>
              <input type="radio" name="label_types" value="4" type="button" onClick="showHideDiv('divMsg')">
              <img src="<?= Yii::getAlias('@web') ?>/img/Barcode2.png" alt="Barcode">
              <div class="card-body ">
                <p class="card-text">Barcode</p>
              </div>
            </label>
          </div>
        </div>
      </div>

      <div class="container mx-auto mt-5">
        <div class="row">
          <div class="col-sm-4">
            <?=
            $form->field($model, 'epsilon')->textInput()->input('text', ['placeholder' => "Epsilón"])->label("Epsilon") ?>

          </div>
          <div class="col-sm-4">
            <?= $form->field($model, 'qtColumns')->textInput()->input('text', ['placeholder' => "Columnas"])->label("Columnas") ?>
          </div>

          <div class="col-sm-4">
            <?= $form->field($model, 'time')->textInput()->input('text', ['placeholder' => "Tiempo"])->label("Tiempo de Apertura (s) ") ?>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="card col-12" id="divMsg">
    <div class="card-body d-flex flex-column">
      

          <img  width="600" height="600" class="d-flex justify-content-center mx-auto" src="<?= Yii::getAlias('@web') ?>/img/type2.png" id="myImg"/>
          <select name="optionType" onchange="renderImage(this.value)" id="selectOption" class="select-css mt-3">
          <option hidden selected value="1"> Tipo 2  </option>
          <option value="1">tipo 2</option>
          <option value="2">tipo 3</option>
          <option value="3">tipo 4</option>
          <option value="4">tipo 6</option>
          </select>
     
            
    </div>
  </div>

</div>
<div class="btns-group"> <a href="#" class="btn btn-prev bg-secondary">Anterior</a>
  <?= Html::submitButton(
    Yii::t('app', 'Save'),
    ['class' => 'btn btn-success btn-danger']
  ) ?>
</div>
<?php ActiveForm::end(); ?>
