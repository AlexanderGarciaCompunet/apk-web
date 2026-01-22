<?php

use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'][] = Html::a(
  'Nuevo',
  ['create'],
  ['class' => 'btn btn-success']
);
$this->params['newButton'] = Html::button(Yii::t('app', 'Update') . '<i class="fas fa-plus ml-2"></i> ', [
  'value' => Url::to(['user/import']),
  'title' => Yii::t('app', 'Importing Users'),
  'class' => 'showModalButton btn btn-primary rounded-pill',
  'size' => 'modal-lg'
]);

?>

<div class="card" style="border-top-width: 18px; border-top-color: #171352; border-radius: 15px;">
  <div class="row">
    <div class="col-12">
      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
          // 'id',
          'fullName',
          'email:email',
          'username',
          // 'auth_key',
          // 'password_hash',
          // 'password_reset_token',
          [
            'attribute' => 'status',
            'label' => Yii::t('app', 'Status'),
            'value' => function ($row) {
              return $row->status == 10 ? 'Activo' : 'Deshabilitado';
            }
          ],
          //'last_name',
          // 'document',
          // 'group',
          // 'zone',
          // 'phone',
          // 'address',
          //'updated_at',
          // 'created_at',
          // 'image',
          // 'path',

          [
            'class' => 'yii\grid\ActionColumn',
            'header'=> 'Acciones',
            'headerOptions'=>[
                  'style'=>'text-aling:center !important;'
            ],
            'template' => '<div class="d-flex justify-content-center">{view}{update}</div>',
            'buttons' => [
              'view' => function ($url) {
                return Html::a(
                  '<span class="fas fa-eye mr-4"></span> ',
                  $url,

                  //  ['class' => 'btn btn-outline-primary btn-sm']
                );
              },
              'update' => function ($url, $model, $key) {
                $target = '_self';
                return Html::a(
                  '<span class="fas fa-user-plus"></span> ',
                  $url,
                  //  ['class' => 'btn btn-outline-primary btn-sm']
                );
              },
            ]
          ],
        ],
        'toolbar' => [
          '{export}',
        ],
        'export' => [
          'fontAwesome' => true
        ],
        'striped' => true,
        'resizableColumns' => true,
        'responsiveWrap' => true,
        'responsive' => true,
        'floatOverflowContainer' => true,
        'panel' => [
          // 'type' => GridView::TYPE_DANGER,
          'after' => false,
        ],
      ]); ?>
    </div>
  </div>
</div>
</div>
</div>
