<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/assets';

    public $css = [
        'css/layout.min.css',
        'css/components.min.css',
        'css/jui.css',
        'css/custom.css',
        'http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
        'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
    ];
    public $js = [
        'plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'js/app.min.js',
        'js/layout.min.js',
        'js/custom.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\jui\JuiAsset',
        '\wfcreations\simplelineicons\AssetBundle'
    ];

    public $themeStyle = 'darkBlue';

    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

    private $_themesCss = [
        'default' => 'css/themes/default.min.css',
        'darkBlue' => 'css/themes/darkblue.min.css',
        'blue' => 'css/themes/blue.min.css',
        'grey' => 'css/themes/grey.min.css',
        'light' => 'css/themes/light.min.css',
        'light2' => 'css/themes/light2.min.css',
    ];

    public function init()
    {
        parent::init();

        if (isset($this->_themesCss[$this->themeStyle])) {
            $this->css[] = $this->_themesCss[$this->themeStyle];
        }

        $am = \Yii::$app->assetManager;
        if ($am->getBundle('yii\jui\JuiAsset')) {
            $am->bundles['yii\jui\JuiAsset']->css = [];
        }
    }
}
