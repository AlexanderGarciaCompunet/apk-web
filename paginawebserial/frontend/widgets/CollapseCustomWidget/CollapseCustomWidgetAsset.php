<?php
// your_app/votewidget/VoteWidgetAsset.php



namespace frontend\widgets\CollapseCustomWidget;

use yii\web\AssetBundle;

class CollapseCustomWidgetAsset extends AssetBundle
{
  public $sourcePath = '@app/widgets/CollapseCustomWidget/assets';
  // public $css = ['main.css'];
  public $js = ['js\CollapseCustomWidget.js'];


  // public $js = [
  //   'CollapseCustomWidget.js'
  // ];
  // public function init()
  // {
  //   // Tell AssetBundle where the assets files are
  //   $this->sourcePath = __DIR__ . "\assets";
  //   var_dump($this->sourcePath);
  //   parent::init();
  // }
}
