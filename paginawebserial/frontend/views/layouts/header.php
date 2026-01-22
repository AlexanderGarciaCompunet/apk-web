<?php

use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $directoryAsset string */

$userId = Yii::$app->user->getId();
$identity = Yii::$app->user->identity;
$name_user = $identity ? $identity->fullname : '';
$user_name = $identity ? $identity->username : '';
$date_member = $identity ? Yii::$app->formatter->asDate($identity->created_at) : '';
$userAssigned = $userId ? Yii::$app->authManager->getAssignments($userId) : [];
?>


<nav class="main-header navbar navbar-expand navbar-light p-md-4 m-0 p-0" style="background-color: #E6E6E6; border-color: #E6E6E6;" role="navigation">

  <ul class="navbar-nav">
    <li class="nav-item">
      <!-- <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a> -->
    </li>
  </ul>

  <!-- SEARCH FORM -->


  <ul class="navbar-nav ml-auto">
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <!--<input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>-->
      </div>
    </form>


    <?=
    Breadcrumbs::widget(
      [
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options' => ['class' => 'bcrumb']
      ]
    ) ?>



  </ul>

</nav>
