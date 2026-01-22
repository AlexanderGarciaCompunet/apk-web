<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Scripts');
$this->params['breadcrumbs'][] = $this->title;


/* @var $this \yii\web\View */
?>
<?php \yii\widgets\Pjax::begin(); ?>
<div class="container">
  <div class="row">
    <div class="col-lg-6 mb-4">
      <div class="card" style=" border-radius: 15px 15px 15px 15px">
        <div class="card-header" style="background-color: #171352; color: #fff; font: normal normal normal 20px/24px 'museo_sans700'; ">
          Descarga de Seriales
        </div>
        <div class="card-body">
          <div class="d-flex align-items-baseline mb-3">
            <?= Html::a(
              'Descargar Seriales' .
                '<i class="fas fa-plus ml-2"></i>',
              ['list-script'],
              $options = ['class' => ' btn btn-danger rounded-pill']
            )
            ?>
          </div>
        </div>
      </div>

    </div>


    <div class="col-lg-6 mb-4">
      <div class="card" style=" border-radius: 15px 15px 15px 15px">
        <div class="card-header" style="background-color: #171352; color: #fff; font: normal normal normal 20px/24px 'museo_sans700'; ">
        Descarga de Prealerta
        </div>
        <div class="card-body">
          <div class="d-flex align-items-baseline mb-3">
            <?= Html::a(
              'Descragar Prealerta' .
                '<i class="fas fa-plus ml-2"></i>',
              ['prealert-list'],
              $options = ['class' => ' btn btn-danger rounded-pill']
            )
            ?>
          </div>
        </div>
      </div>

    </div>
  </div>



  <br>
