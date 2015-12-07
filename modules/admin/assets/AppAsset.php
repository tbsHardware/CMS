<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/assets';

    public $css = [
        'css/layout.min.css',
    ];
    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        '\wfcreations\simplelineicons\AssetBundle'
    ];

    public $themeStyle = 'darkBlue';

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
