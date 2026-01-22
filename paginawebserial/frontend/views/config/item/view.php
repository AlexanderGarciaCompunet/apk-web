<?php

use mdm\admin\AnimateAsset;
use mdm\admin\components\ItemController;
use mdm\admin\models\AuthItem;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var $this View */
/* @var $model AuthItem */
/* @var $context ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = 'Modificación de Rol';
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
  'items' => $model->getItems(),
  'users' => $model->getUsers(),
  'getUserUrl' => Url::to(['get-users', 'id' => $model->name])
]);

$this->params['newButton'] = Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-success']).' ' .
Html::a(Yii::t('app', 'Save'), ['#'], ['class' => 'btn btn-danger']);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
// var_dump($model->name);
?>


<div class="auth-item-view">

  

  <div class="row">
    <div class="col-sm-11">
      <?=
      DetailView::widget([
        'model' => $model,
        'attributes' => [
          'name',
          'description:ntext',
          // 'ruleName',
        ],
        'template' => '<tr><th style="width:20%">{label}</th><td>{value}</td></tr>',
      ]);
      ?>
    </div>
  </div>


  <div class="row">
    <div class="col-sm-11">
      <table id="w0" class="table table-striped table-bordered detail-view">
        <tbody>
          <tr>
            <th style="width:20%"><?= Yii::t('app', 'Assigned Users'); ?></th>
            <td id="list-users"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-5">
    <input class="form-control search" data-target="available" placeholder="<?= Yii::t('rbac-admin', 'Search for available'); ?>">
    <select multiple size="20" class="form-control list" data-target="available"></select>
  </div>
  <div class="col-sm-1">
    <br><br>
    <?=
    Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => $model->name], [
      'class' => 'btn btn-success btn-assign',
      'data-target' => 'available',
      'title' => Yii::t('rbac-admin', 'Assign'),
    ]);
    ?><br><br>
    <?=
    Html::a('&lt;&lt;' . $animateIcon, ['remove', 'id' => $model->name], [
      'class' => 'btn btn-danger btn-assign',
      'data-target' => 'assigned',
      'title' => Yii::t('rbac-admin', 'Remove'),
    ]);
    ?>
  </div>
  <div class="col-sm-5">
    <input class="form-control search" data-target="assigned" placeholder="<?= Yii::t('rbac-admin', 'Search for assigned'); ?>">
    <select multiple size="20" class="form-control list" data-target="assigned"></select>
  </div>
</div>
</div>
