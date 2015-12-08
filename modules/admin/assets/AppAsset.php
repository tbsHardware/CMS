<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/assets';

    public $css = [
        'css/layout.min.css',
        'css/components.min.css',
        'http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
        'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
    ];
    public $js = [
        'js/app.min.js',
        'js/bootstrap-hover-dropdown.min.js',
        'js/jquery.slimscroll.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
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
    }
}
