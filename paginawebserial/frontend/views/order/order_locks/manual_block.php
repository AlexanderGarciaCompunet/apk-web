<?php

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

<?php $form = ActiveForm::begin(['action' => ['unlock-order-by-status', 'id' => $model->id]]); ?> 
<div class="row">
  <div class="col-md-12" align="center">

    <?php if ($model->status == 18) {
      echo '<h3 class="text-success">¿Está seguro que desea desbloquear el pedido?</h3>';

      echo Html::submitButton(
        'Desbloquear',
        ['class' => 'btn btn-success btn-success']
      );
    } else {
      echo '<h3 class="text-danger">¿Está seguro que desea bloquear el pedido?</h3>';

      echo Html::submitButton(
        'Bloquear',
        ['class' => 'btn btn-success btn-danger']
      );
    }
    ?>

  </div>
</div>

<?php ActiveForm::end(); ?>
