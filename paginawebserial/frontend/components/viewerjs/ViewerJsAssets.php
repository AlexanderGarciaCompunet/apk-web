<?php


namespace frontend\components\viewerjs;


use yii\web\AssetBundle;
use yii\web\View;

class ViewerJsAssets extends AssetBundle
{

   // public $basePath = '@webroot';
    public $sourcePath = '@bower/viewerjs/';
    //public $baseUrl = __DIR__ . '/assets';
    public $css = [
    ];
    public $js = [
       /* 'compatibility.js',
        'pdf.js',
        'pdf.worker.js',
        'pdfjsversion.js',
        'text_layer_builder.js',
        'ui_utils.js',
        'webodf.js',*/
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];

}
