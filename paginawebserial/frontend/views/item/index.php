<?php

use common\models\Company;
use common\models\Customer;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Items');
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('/item/import')) {
  $this->params['newButton'] = Html::button(Yii::t('app', 'Update') . '<i class="fas fa-plus ml-2"></i> ', [
    'value' => Url::to(['item/import']),
    'title' => Yii::t('app', 'Importing Data'),
    'class' => 'showModalButton btn btn-danger rounded-pill',
    'size' => 'modal-lg'
  ]);
}
?>
<div id="container">
  <div class="card-wrapper">
    <div class="card" style="border-top-width: 18px; border-top-color: #171352; border-radius: 15px;">
      <div class="row">
        <div class="col-12">
          <?php Pjax::begin(); ?>

          <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['class' => '',],
            'columns' => [
              ['class' => 'yii\grid\SerialColumn',
              // 'contentOptions'=>['style'=>'margin: auto !important; background-color:blue;']
              ],
              [
                'label' => Yii::t('app', 'Customer'),
                'attribute' => 'customer_id',
                'value' => function ($model) {
                  return $model->customer->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Customer::find()->where(['status'=>20])->asArray()->all(), 'id', 'name'),
                'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => Yii::t('app', 'Select ...'),]
              ],
              'code',
              'name',
              [
                'attribute' =>  'netweigth',
                'label' => Yii::t('app', 'Peso Neto'),
                'value' => function ($model) {
                  return $model->netweigth ?? '-';
                },
              ],

              [
                'attribute' =>  'unitnet',
                'label' => Yii::t('app', 'Unidad'),
                'value' => function ($model) {
                  return $model->unitnet ?? '-';
                },
              ],

              [
                'attribute' =>  'grweigth',
                'label' => Yii::t('app', 'Peso Bruto'),
                'value' => function ($model) {
                  return $model->grweigth ?? '-';
                },
              ],

              [
                'attribute' =>  'unitgrw',
                'label' => Yii::t('app', 'Unidad'),
                'value' => function ($model) {
                  return $model->unitgrw ?? '-';
                },
              ],
              [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                  'view' => function ($url) {
                    return Html::a(
                      '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"></path></svg>',
                      $url,
                      [
                        'class'=>'mx-2 ',
                        'title' => 'Editar Material',
                      ],
                    );
                  },
                ]
              ],
            ],
            'toolbar' => [
              '{export}',
              '{toggleData}',
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
              'after' => false,
            ],
          ]); ?>

          <?php Pjax::end(); ?>
        </div>

      </div>
    </div>
  </div>
</div>
