<?php
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log de Ingreso';
$this->params['breadcrumbs'][] = $this->title;
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'userId',
        'label' => Yii::t('app', 'User'),
        'value' => function($model){
            if ($model->user)
            {return $model->user->fullname;}
            else
            {return NULL;}
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'fullname'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid--user_id']
    ],
    'date:datetime',
    'duration',
    [
        'label' => 'Ip',
        'format' => 'raw',
        'value' => function ($model) {
            $ip = '';
            if ($model->ip != '')
                $ip = Html::a($model->ip, 'https://aruljohn.com/ip/'. $model->ip, ['class' => 'btn btn-primary btn-flat', 'target' => '_blank']) . '<br/>';
            return $ip;
        }
    ],
    'host',
    'userAgent',
];
?>
<div class="box">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    'pdf'=>false,
                    'xls'=>false,
                    'csv'=>false,
                    'txt'=>false
                ]
            ]) ,
        ],
        'striped'=>true,
        'resizableColumns' => true,
        'responsiveWrap' => false,
        'responsive' => true,
        'floatOverflowContainer'=>true,
    ]); ?>
</div>
