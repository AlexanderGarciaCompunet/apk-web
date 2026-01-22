<?php


/* @var $model yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use yii\widgets\Pjax;

$this->title='Materiales Asociados'
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['label'=>'Cliente',
        'value'=>'customer'
        ],
        ['label'=>'Código',
        'value'=>'code'
        ],
        ['label'=>'Nombre de Material',
        'value'=>'name'
        ],
        ['label'=>'Peso',
        'value'=>'netweigth'
        ],
        ['label'=>'Unidad',
        'value'=>'unitnet',
        ],
    ]
    
]); ?>