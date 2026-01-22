<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class InputsAsset extends AssetBundle
{
  private $timesocket;
  public $basePath = '@webroot';
  public $baseUrl = '@web';
  public $css = [
    'css/inputs.css',
  ];
  public $js = [];
  public $depends = [
    'yii\web\YiiAsset',
    'yii\bootstrap4\BootstrapAsset',
  ];
}
