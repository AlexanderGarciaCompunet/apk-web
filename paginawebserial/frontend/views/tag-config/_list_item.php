<?php

use yii\bootstrap\Collapse;
use yii\helpers\Html;


/* @var $model yii\data\ActiveDataProvider */
    
?>

<?php echo frontend\widgets\CollapseCustomWidget\CollapseCustomWidget::widget(['model' => $model, 'index'=> $index]) ?>
