<?php

use kartik\grid\GridView;
use kartik\helpers\Html;
use common\models\SystemConfig;

use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\DocumentHeader */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title =  Yii::t('app', 'Reference List') . ' ' . $model->docnr;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Document Headers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
// var_dump(Yii::$app->user->identity->id);

$this->params['newButton'] = Html::button('<i class="fas fa-plus mr-2"></i>' . Yii::t('app', 'Assigning Users'), [
  'value' => Url::to(['view-users', 'id' => $model->id]),
  'title' => Yii::t('app', 'Assigning Users'),
  'class' => 'showModalButton btn btn-success',
  'size' => 'modal-lg'
]);



switch ($model->status) {
    case 14:
      case 12: 
        case 15:
    $this->params['addButton'] = Html::button('<i class="fas fa-lock mr-2"></i>' . 'Bloquear Pedido', [
      'value' => Url::to(['show-modal-unlock', 'id' => $model->id]),
      'title' => 'Confirmación de bloqueo',
      'class' => 'showModalButton btn btn-danger',
      'size' => 'modal-lg'
    ]);
    break;

  case 18:
      $this->params['addButton'] = Html::button('<i class="fas fa-lock-open mr-2"></i>' . 'Desbloquear Pedido', [
        'value' => Url::to(['show-modal-unlock', 'id' => $model->id]),
        'title' => 'Confirmación de desbloqueo',
        'class' => 'showModalButton btn btn-success',
        'size' => 'modal-lg'
      ]);
      break;

  case 19:
        $this->params['addButton'] = Html::button('<i class="fas fa-lock mr-2"></i>' . 'Desbloqueo por Cantidades', [
          'value' => Url::to(['unlock-amounts', 'id' => $model->id]),
          'title' => 'Confirmación de Desbloqueo Por Cantidades',
          'class' => 'showModalButton btn btn-danger',
          'size' => 'modal-lg'
        ]);
        break;
}


?>
<?= $this->render('_view-stats', ['dataProvider' => $dataProvider, 'model' => $model]) ?>
<div class="row">
  <div class="col-12">

    <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
          'label' => 'Material',
          'value' => function ($model) {

            return Html::a($model->item->code . ' ' . $model->item->name, ['/item/view', 'id' => $model->item_id]);
          },
          'format' => 'raw'
        ],
        'amount',
        
        [
          'label' => 'Cantidad Leida',
          'value'=>'real_amount',
          'format' => 'raw'

        ],
        'unit',
        'invlin',
        'rcvsts',

        [
          'label' => 'Estado',
          'value' => function ($model) {
            $conf = SystemConfig::findOne(['type' => 'status', 'reference' => 'orders']);
            $data = json_decode($conf->value, true);
            return Html::bsLabel($data[$model->status]['label'], $data[$model->status]['color']);
          },
          'format' => 'html'
        ],


      ],
      // 'rowOptions' =>  function ($model, $key, $index, $column) {
      //   if ($index % 2 == 0) {
      //     return ['class' => 'bg-info'];
      //   }
      // },

      'toolbar' => [
        [
          'content' =>
          Html::a('<i class="fas fa-file"></i>', ['import-by-txt', 'id' => $model->id], [
            'class' => 'btn btn-outline-secondary btn-default',
            'title' => 'import file',
            'options' => ['class' => 'btn-group']
          ]).' '.
          Html::a('<i class="fas fa-download"></i>', ['export-all-record', 'id' => $model->id ,'order'=>$model->docnr], [
            'class' => 'btn btn-outline-secondary btn-default',
            'title' => 'export record',
            'options' => ['class' => 'btn-group']
          ]),
        ],
        // '{export}',
      ],

      'responsive' => true,
      'panel' => [
        'type' => GridView::TYPE_DANGER,
        'heading' => '<i class="fas fa-book"></i>  ' . Yii::t('app', 'List'),
        'after' => false,
      ],
    ]);
    ?>

  </div>
</div>
