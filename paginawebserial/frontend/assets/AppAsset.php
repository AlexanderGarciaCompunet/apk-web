<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
  private $timesocket;
  public $basePath = '@webroot';
  public $baseUrl = '@web';
  public $css = [
    'css/site.css',
    'css/font.css',
  ];
  public $js = [
    // 'js/socket.io.js', // MODO DEMO: deshabilitado
    'js/vue.global.js'
  ];
  public $depends = [
    'yii\web\YiiAsset',
    'yii\bootstrap4\BootstrapAsset',
  ];

  public function init()
  {
    $this->timesocket = Yii::$app->params['timesocket'] ?? null;
    $this->js[] =   ['js/modals.js', 'data-m' => $this->timesocket];
    parent::init();
  }
}
