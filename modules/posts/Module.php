<?php

namespace app\modules\posts;

use Yii;
use yii\base\Module as BaseModule;
use yii\base\BootstrapInterface;
use app\modules\posts\models\Post;

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
        if ($this->rules)
        {
            $aliases = implode('|', $this->getAliasesOfMainPages());
            if ($aliases)
            {
                $rules = [];
                foreach ($this->rules as $key => $value)
                {
                    $key = str_replace('{aliases}', $aliases, $key);
                    $rules[$key] = $value;
                }
                $app->getUrlManager()->addRules($rules, false);
            }
        }
    }

    public function getAliasesOfMainPages()
    {
        $aliases = [];
        $posts = Post::find()->byPublished()->byPage()->byParent()->select('post_alias')->all();
        if ($posts)
        {
            foreach ($posts as $post)
                $aliases[] = $post->post_alias;
        }
        return $aliases;
    }

}