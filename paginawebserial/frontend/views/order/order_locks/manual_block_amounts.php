<?php

use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


// \frontend\assets\RoleAsset::register($this);

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Assignment */
/* @var $fullnameField string */
/*
$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
$userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = Yii::t('rbac-admin', 'Assignment') . ' : ' . $userName;
*/

?>

<?= GridView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => '',],
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              // 'item_id',
              [
                'label' => 'Cantidad  Real',
                'attribute' => 'real_amount',
                ],
              [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'pivot_amount',
                'label' => 'Cantidad  Teorica',
                'pageSummary' => true,
                'readonly' => false,
                'value' => function($model){ return $model->pivot_amount; }, // assign value from getProfileCompany method
                'editableOptions' => [
                     'header' => 'pivot_amount',
                     'inputType' => kartik\editable\Editable::INPUT_TEXT,
                     'options' => [
                         'pluginOptions' => [
             
                         ]
                     ]
                 ],
                ],
            ],
     
          ]); ?>

          <?php $form = ActiveForm::begin(['action' => ['test', 'id' => $model->id]]); ?> 
<div class="row">
          <div class="col-md-12" align="center">
              <?=Html::submitButton(
                'Desbloquear',
                ['class' => 'btn btn-success ']
              );
            ?>
          </div>
        </div>
        <?php ActiveForm::end(); ?>