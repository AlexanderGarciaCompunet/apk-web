<?php

use common\models\Item;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Store */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Stores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

if (Yii::$app->user->can('assign_user_store')) {
  $this->params['newButton'] = Html::button(Yii::t('app', 'Assigning Users') . '<i class="fas fa-plus ml-2"></i>', [
    'value' => Url::to(['view-users', 'id' => $model->id]),
    'title' => Yii::t('app', 'Assigning Users'),
    'class' => 'showModalButton btn btn-danger rounded-pill',
    'size' => 'modal-lg'
  ]);
}
?>

<div class="row justify-content-center mt-3">
  <div class="col-md-12">
    <div class="card">
      <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          'name',
          'description',
          [
            'attribute' =>  'customer.name',
            'label' => Yii::t('app', 'Customer')
          ],
        ],
      ]) ?>

      <div class="card-header">
        <h5 class="text-center"><?= Html::encode(Yii::t('app', 'Assigned Users')) ?></h5>
      </div>

      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}{pager}',
        'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
          ['attribute' => 'user.fullName', 'label' => Yii::t('app', 'Full Name')],
          ['attribute' => 'created_at', 'label' => Yii::t('app', 'Date of Assignment')],
        ],
      ]); ?>
    </div>
  </div>
</div>
</div>
