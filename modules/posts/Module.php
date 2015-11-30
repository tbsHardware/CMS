<?php

namespace app\modules\posts;

use Yii;
use yii\base\Module as BaseModule;
use yii\base\BootstrapInterface;

class Module extends BaseModule implements BootstrapInterface
{
    public $rules = [
        '<parent:({aliases})>'                          => 'posts/post/index',
        '<parent:({aliases})>/<id:\d+>'                 => 'posts/post/index',
        '<parent:({aliases})>/<child:[\w\-]+>'          => 'posts/post/index',
        '<parent:({aliases})>/<child:[\w\-]+>/<id:\d+>' => 'posts/post/index',
    ];

    public function bootstrap($app)
    {
        $aliases = implode('|', $this->getAliasesOfMainPages());

         $app->getUrlManager()->addRules([
             '<parent:(' . $aliases . ')>'                          => 'posts/post/index',
             '<parent:(' . $aliases . ')>/<id:\d+>'                 => 'posts/post/index',
             '<parent:(' . $aliases . ')>/<child:[\w\-]+>'          => 'posts/post/index',
             '<parent:(' . $aliases . ')>/<child:[\w\-]+>/<id:\d+>' => 'posts/post/index',
         ], false);
    }

    public function getAliasesOfMainPages()
    {
        return ['contact', 'news', 'primer']; // Тестовый
    }

}