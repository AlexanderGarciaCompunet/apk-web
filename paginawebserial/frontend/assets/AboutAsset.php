<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AboutAsset extends AssetBundle
{
  private $timesocket;
  public $basePath = '@webroot';
  public $baseUrl = '@web';
  public $css = [
    'css/about.css',
  ];
  public $js = [];
  public $depends = [
    'yii\web\YiiAsset',
    'yii\bootstrap4\BootstrapAsset',
  ];

  public function init()
  {
    $this->js[] =   ['js/modals.js', 'data-m' => $this->timesocket];
    parent::init();
  }
}
