<?php

/* @var $this yii\web\View */
// frontend\assets\ZoomAsset::register($this);
use yii\helpers\Html;

$this->title = Yii::t('app', 'General Information');
$this->params['breadcrumbs'][] = $this->title;

use \frontend\assets\AboutAsset;

AboutAsset::register($this);


use \frontend\assets\FontsAsset;

FontsAsset::register($this);

?>
<div class="site-about">

  <div class="container">
    <div class="row justify-content-center">
      <div class="column mb-5">
        <img src="../img/logo_compunet_color.png" class="img" alt="COMPUNET" />
      </div>
    </div>

    <div class="container text-justify">
      <div class="row">
        <h5 class="mb-3 ">Compunet es líder en la integración de tecnología informática, ofreciendo soluciones integrales que abarcan todos los procesos de negocio de las empresas, mejorando su rendimiento y fortaleciendo su competitividad.</h5>
        <br />

        <h5>Conectamos tu negocio con la economía digital a través de nuestros servicios de Tecnología, Soluciones Empresariales, Automatización y Gestión, Servicios Digitales y Soporte SAP ERP. Contamos con alianzas estratégicas con los principales proveedores de tecnología a nivel global.</h5>

        <br />
        <h5>Este software permite la optimización del proceso de toma masiva de seriales para las referencias que posean esta característica (serializadas) con la integración al software de WMS (Warehouse Management System).</h5>

      </div>
    </div>

  </div>
</div>
