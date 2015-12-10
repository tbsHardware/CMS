<?php

namespace app\components;

use Yii;
use yii\i18n\PhpMessageSource;

class BaseModule extends \yii\base\Module
{
    public $sourceLanguage = 'en-US';

    public function init()
    {
        parent::init();

        if(file_exists($this->basePath . '/messages')) {
            $i18n = Yii::$app->get('i18n');
            if ($i18n) {
                if (!isset($i18n->translations[$this->id])) {
                    $i18n->translations[$this->id] = [
                        'class' => PhpMessageSource::className(),
                        'basePath' => $this->basePath . '/messages',
                        'sourceLanguage' => $this->sourceLanguage,
                    ];
                }
            }
        }
    }
}