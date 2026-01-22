<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use \frontend\assets\ConfigItemAsset;
use kartik\grid\GridView;
use yii\helpers\Url;

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $modelRule common\models\SerialRules */
/* @var $serialTypes common\models\SerialType */

ConfigItemAsset::register($this);

$this->title = Yii::t('app', '{name}', [
  'name' => $model->name
]);
$this->params['subtitles'] =  Yii::t('app', 'Código : {code}', [
  'code' => $model->code
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->code;

\yii\web\YiiAsset::register($this);
?>


<div class="container col-11">
  <?php $form = ActiveForm::begin(['action' => ['/item/view', 'id' => $model->id]]); ?>
  <div class="progressbar">
    <div class="progress" id="progress"></div>
    <div class=" progress-step progress-step-active" data-title="Material"></div>
    <div class="progress-step" data-title="Configuración de etiqueta"></div>
  </div>
  <div class="step-forms step-forms-active">
    <div class="row justify-content-around mt-4">
      <div class="col-md-6">
        <?= $this->render('/rule/_form', [
          'model' => $modelRule, 'serialTypes' => $serialTypes,
          'form' => $form
        ]) ?>
      </div>

      <div class="col-md-6 ">
        <?= $this->render('_form', [
          'model' => $model,
          'form' => $form
        ]) ?>
      </div>

    </div>

    <br>

    <div class="row col-12 justify-content-around p-0 m-0">
      <div class="col-6 p-0"></div>
      <div class="card col-6 bg-danger p-0">
        <a href="#" class="btn btn-next bg-danger">Siguiente</a>
      </div>
    </div>



  </div>


  <div class="step-forms">
    <div class="row justify-content-around p-0 m-0">
      <div class="col-10">


      <div class="row pb-3">
          <div class="col-sm-8">
          <h1 class="h5"></h1>
        </div>

        <div class="col-sm-4 d-flex justify-content-end">
          <?php echo Html::button('Asignación de etiquetas' . '<i class="fas fa-plus ml-2"></i> ', [
          'value' => Url::to(['view-stores', 'id' => $model->id]),
          'title' => 'Asignación de etiquetas',
          'class' => 'showModalButton btn btn-danger rounded-pill',
          'size' => 'modal-lg'
        ])?>
        </div>
      </div>
     
        <?php Pjax::begin(['id' => 'test']); ?>

        <?= GridView::widget([
          'dataProvider' => $dataProvider,
          'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'label'=>'Nombre',
              'value'=>'name',
            ],

            [
              'label'=>'Descripción',
              'value'=>'description',
            ],
          ],
          'export' => false,
          'panel' => [
            'type' => GridView::TYPE_DANGER,
            'heading' => '<i class="fas fa-tags"></i> ',
          ],
        ]); ?>
        <?php Pjax::end(); ?>

      </div>
    </div>


    <div class="btns-group"> <a href="#" class="btn btn-prev bg-secondary">Anterior</a>
      <?= Html::submitButton(
        Yii::t('app', 'Save'),
        ['class' => 'btn btn-success btn-danger']
      ) ?>
    </div>

  </div>
  <?php ActiveForm::end(); ?>