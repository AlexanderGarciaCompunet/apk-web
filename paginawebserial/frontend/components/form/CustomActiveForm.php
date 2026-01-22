<?php

namespace frontend\components\form;

use kartik\form\ActiveForm;

class CustomActiveForm extends  ActiveForm
{

  public $icon;

  public function field($model, $attribute, $options = [])
  {
    $options = [
      'template' => '
      {label}{input}{hint}{error}' . $this->icon
    ];

    return parent::field($model, $attribute, $options);
  }
}

// $option = [
  // 'template' => "{label}\n<i class='fa fa-user'></i>\n{input}\n{hint}\n{error}"
// ];
