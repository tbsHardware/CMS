<?php

namespace app\components;

use Yii;
use yii\i18n\PhpMessageSource;

class BaseModule extends \yii\base\Module
{
    public $sourceLanguage = 'en-US';

    protected $_migrationsPath = false;

    public function init()
    {
        parent::init();

        Yii::setAlias('@' . $this->id, $this->basePath);

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

    public function getMigrationsPath()
    {
        if($this->_migrationsPath === false) {
            $this->_migrationsPath = $this->basePath . '/migrations';
            if(!file_exists($this->_migrationsPath)) {
                $this->_migrationsPath = null;
            }
        }
        return $this->_migrationsPath;
    }
}