<?php

use common\models\Customer;
use common\models\Store;
use common\models\SystemConfig;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use kartik\dropdown\DropDownX;





/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\DocumentHeader */
$this->title = Yii::t('app', 'Document Headers');
$this->params['breadcrumbs'][] = $this->title;
// $this->params['serialButton'] = Html::a('Seriales' . '<i class="fas fa-barcode ml-2"></i>', ['show-serials'], $options = ['class' => 'btn btn-primary  rounded-pill']);

$this->params['serialButton']=  Html::button('Ver Seriales <span class="caret"></span></button>', 
['type'=>'button', 'class'=>'btn btn-default btn-outline-secondary', 'data-toggle'=>'dropdown']).''.
 DropdownX::widget([
'options'=>['class'=>'pull-right'], // for a right aligned dropdown menu
'items' => [
  ['label' => 'Ver Seriales', 'url' => 'show-serials'],
    ['label' => "Ver Lpns", 'url' => 'show-lpns'],
],
]); 

if (Yii::$app->user->can('/order/import')) {
  $this->params['newButton'] = Html::button(Yii::t('app', 'Update') . '<i class="fas fa-plus ml-2"></i>', [
    'value' => Url::to(['order/import']),
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
            'options' => ['class' => '',],
            'filterModel' => $searchModel,
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              'docnr',

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
              [
                'label' => Yii::t('app', 'Store'),
                'attribute' => 'store_id',
                'value' => function ($model) {
                  return $model->store->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Store::find()->asArray()->all(), 'id', 'name'),
                'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => Yii::t('app', 'Select ...'),]
              ],

              'type',
              // 'created_at',
              'docaxnr',
              'orgcod',
              [
                'label' => 'Creado en',
                'attribute' => 'created_at',
                'value' => 'created_at',
                'format' => 'datetime',
                'filter' => DateRangePicker::widget([
                  'model' => $searchModel,
                  'attribute' => 'created_at',
                  'convertFormat' => true,
                  'pluginOptions' => [
                    'locale' => [
                      'format' => 'Y-m-d'
                    ],
                  ],
                ]),
        
              ],
              [
                'label' => 'Estado',
                'value' => function ($model) {
                  $conf = SystemConfig::findOne(['type' => 'status', 'reference' => 'orders']);
                  $data = json_decode($conf->value, true);
                  return Html::bsLabel($data[$model->status]['label'], $data[$model->status]['color']);
                },
                'format' => 'html'
              ],
              [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                  'view' => function ($url, $model, $key) {
                    $target = '_self';
                    return Html::a(
                      '<span class="fas fa-eye"></span> ',
                      $url,
                      [
                        'title' => 'Ver Pedido',
                        'target' => $target,
                        'data-pjax' => '0',
                      ],
                    );
                  },
                ]
              ],
            ],
            'toolbar' => [
              [

                //     'options' => ['class' => 'btn-group mr-2']
              ],
              '{export}',
              //   '{toggleData}',
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
              //'type' => GridView::TYPE_DANGER,
              //'heading' => '<i class="fas fa-book"></i>  ' . Yii::t('app', 'List'),
              'after' => false,
            ],
          ]); ?>

          <?php Pjax::end(); ?>
        </div>

      </div>
    </div>
  </div>
</div>


<!-- <?php $form = ActiveForm::begin([
        'action'  => ['search'],
        'method'  => 'get',
        'options' => ['class' => 'form-inline'],
      ]); ?>
<div class="form-group">
  <label class="control-label" for="search">Search: </label>
  <input id="search" name="search" placeholder="Search Here" class="form-control input-md" required value="" type="text">
</div>
<div class="form-group">
  <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?> -->
