<?php

namespace app\modules\posts;

use app\modules\posts\models\Page;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;

class Module extends BaseModule implements BootstrapInterface
{
    public $rules = [
        '<parent:({paths})>/<postId:[\d]+>/<postAlias:[\w_-]>' => 'posts/post/index',
        '<parent:({paths})>/<page:[\w_\/-]+>/<id:[\d]+>/<postAlias:[\w_-]>' => 'posts/post/index',
        '<parent:({paths})>/<page:[\w_\/-]+>' => 'posts/page/index',
        '<page:({paths})>' => 'posts/page/index',
    ];

    public function bootstrap($app)
    {
        if ($this->rules) {
            if ($paths = $this->getPathsOfMainPages()) {
                $rules = [];
                $paths = implode('|', $paths);
                foreach ($this->rules as $key => $value) {
                    $key = str_replace('{paths}', $paths, $key);
                    $rules[$key] = $value;
                }
                $app->getUrlManager()->addRules($rules, false);
            }
        }
    }

    public function getPathsOfMainPages()
    {
        $paths = [];
        if (Yii::$app->db->schema->getTableSchema(Page::tableName())) {
            $pages = Page::find()->parent()->select('page_path')->all();
            if ($pages) {
                foreach ($pages as $page) {
                    $paths[] = $page->page_path;
                }
            }
        }
        return $paths;
    }

}