<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\SerialList;
use frontend\assets\AppAsset;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

AppAsset::register($this);

$this->title='Edición de Seriales'

/* @var $this yii\web\View */
/* @var $model common\models\DocumentHeader */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="document-header-form">

  <?= Html::a('Atras', Yii::$app->request->referrer); ?>


  <div class="row">
    <div class="col">
      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'searchModel' => $searchModel,
        'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
          [
            'label'=>'Serial',
            'value'=>'value'
          ],
          'created_at',
        ],

        'toolbar' => [
          '{export}',
        ],
        'export' => [
          'fontAwesome' => true
        ],
        'panel' => [
          'type' => GridView::TYPE_DANGER,
          'heading' => '<i class="fas fa-book"></i>  ' . Yii::t('app', 'List'),
          'after' => false,
        ],
      ]);
      ?>


    </div>
    <div class="col">
      <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => ['/order/upload']]); ?>

      <?= $form->field($model, 'txt_path')->widget(FileInput::classname(), [
        'options' => ['accept' => 'text/*'],
      ]);
      ?>
      <?php ActiveForm::end(); ?>
    </div>


  </div>
