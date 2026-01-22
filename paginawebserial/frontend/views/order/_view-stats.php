<?php
/* @var $data \yii\helpers\Json */
/* @var $model common\models\DocumentHeader */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\web\View;

$js = "
var pos = {$model->id};
var nowTotal =  {$model->totalAmount};
var nowReadings =  {$model->totalReadings};
var nowPercent = {$model->totalPercent};
const Counter = {
  data() {
    return {
      capture: nowReadings,
      total: nowTotal,
      users: 0,
      percent: nowPercent
    }
  },
  beforeCreate() {
    socket.on('getTotalOrder', (data) => {
      console.log(data);
      if (data.ord_id == pos) {
        this.capture = data.value;
        this.percent = ((data.value / this.total) * 100).toFixed(2) 
      }
    })
    socket.on('qtUsers',(data)=>{
      if (data.order == pos) {
        this.users=data.users;
      }
    })
  },


  created() {
    socket.emit('qtUsers', {idOrder: pos})
  },

}

Vue.createApp(Counter).mount('#stats')
";
$this->registerJs($js, View::POS_END);

?>

<div id="stats" class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1">

        <img src="../img/boxes.png" class="img-fluid" alt="boxes" width="40" height="40" style="color: #fff">

      </span>

      <div class="info-box-content">
        <span class="info-box-text">Total Materiales</span>
        <span class="info-box-number">
          <?= $dataProvider->getTotalCount() ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1">
        <img src="../img/percent.png" class="img-fluid" alt="percent" width="40" height="40" style="color: #fff">

      </span>

      <div class="info-box-content">
        <span class="info-box-text">Porcentaje de Progreso</span>
        <span class="info-box-number">{{ percent }}<small>%</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-warning elevation-1">

        <img src="../img/barcode.png" class="img-fluid" alt="barcode" width="40" height="40" style="color: #fff">

      </span>

      <div class="info-box-content">
        <span class="info-box-text">Total Lecturas</span>
        <span class="info-box-number">{{ capture }}</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">

      <span class="info-box-icon bg-success text-white ">
        <img src="../img/group.png" class="img-fluid" alt="users" width="40" height="40" style="color: #fff">

      </span>

      <div class="info-box-content">
        <span class="info-box-text">Usuarios Conectados</span>
        <span class="info-box-number">{{users}}</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>
