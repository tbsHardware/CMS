<?php

namespace app\modules\post;

use app\modules\post\models\Page;
use Yii;
use yii\base\BootstrapInterface;
use app\components\BaseModule;

class Module extends BaseModule implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\post\controllers';

    public $rules = [
        '<parent:({paths})>/<postId:[\d]+>/<postAlias:[\w_-]>' => 'post/post/index',
        '<parent:({paths})>/<page:[\w_\/-]+>/<id:[\d]+>/<postAlias:[\w_-]>' => 'post/post/index',
        '<parent:({paths})>/<page:[\w_\/-]+>' => 'post/page/index',
        '<page:({paths})>' => 'post/page/index',
    ];

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\post\commands\ConsoleController';
        } elseif ($this->rules) {
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
            $pages = Page::find()->parent()->select('path')->all();
            if ($pages) {
                foreach ($pages as $page) {
                    $paths[] = $page->path;
                }
            }
        }
        return $paths;
    }
}