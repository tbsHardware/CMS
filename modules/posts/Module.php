<?php

namespace app\modules\posts;

use Yii;
use yii\base\Module as BaseModule;
use yii\base\BootstrapInterface;

class Module extends BaseModule implements BootstrapInterface
{
    public $postUrl = 'posts/post/index';

    public function bootstrap($app)
    {
        $aliases = implode('|', $this->getAliasesOfMainPages());

         $app->getUrlManager()->addRules([
             '<parent:(' . $aliases . ')>'                          => $this->postUrl,
             '<parent:(' . $aliases . ')>/<id:\d+>'                 => $this->postUrl,
             '<parent:(' . $aliases . ')>/<child:[\w\-]+>'          => $this->postUrl,
             '<parent:(' . $aliases . ')>/<child:[\w\-]+>/<id:\d+>' => $this->postUrl,
         ], false);
    }

    public function getAliasesOfMainPages()
    {
        return ['contact', 'news', 'primer']; // Тестовый
    }

}