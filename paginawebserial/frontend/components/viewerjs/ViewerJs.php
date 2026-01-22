<?php

namespace frontend\components\viewerjs;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is just an example.
 */
class ViewerJs extends \yii\base\Widget
{
    public $_urlViewer = null;
    public $url;
    public $height = '100%';
    public $width = '100%';

    public function init() {
        parent::init();
    }
    public function run() {
        $this->_urlViewer = Yii::$app->assetManager->getPublishedUrl('@vendor/bower-asset/viewerjs') . '/ViewerJS/index.html?zoom=page-width';
        return $this->registerAssets();
    }

    /**
     * Registers the client assets for [[Dialog]] widget.
     */
    public function registerAssets()
    {
        $view = $this->getView();
        ViewerJsAssets::register($view);
        $options = [
            'src'=>$this->_urlViewer.'#'.$this->url,
            'width'=>$this->width,
            'height'=>$this->height,
            'allowfullscreen',
            'webkitallowfullscreen'
        ];

        return Html::tag('iframe','',$options);

    }
}
