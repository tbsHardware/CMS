<?php

namespace app\modules\post;

use Yii;
use yii\base\BootstrapInterface;


class Module extends \yii\base\Module implements BootstrapInterface
{
    public function init()
    {
        parent::init();

    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $aliases = ['contact', 'news', 'primer']; // getMainPagesAlias()
        $aliases = implode('|', $aliases);

        $app->getUrlManager()->addRules([
            $this->id => $this->id,
            $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>' => $this->id . '/<controller>/<action>',
            '<parent:[' . $aliases . ']>' => $this->id . '/post/index',
            '<parent:[' . $aliases . ']>/<id:\d+>' => $this->id . '/post/index',
            '<parent:[' . $aliases . ']>/<page:[\w\-]+>' => $this->id . '/post/index',
            '<parent:[' . $aliases . ']>/<page:[\w\-]+>/<id:\d+>' => $this->id . '/post/index',
        ], false);
    }
}