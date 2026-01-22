<?php

namespace frontend\widgets\CollapseCustomWidget;

use yii\base\Widget;


class CollapseCustomWidget extends Widget
{

  public $model;
  public $index;
  public function init()
  {
    parent::init();
  }

  public function run()
  {
    // Register AssetBundle
    CollapseCustomWidgetAsset::register($this->getView());
    return $this->render('_collapse', ['tag' => $this->model, 'index'=>$this->index, ]);

    // // no sirve xd
  }
}
