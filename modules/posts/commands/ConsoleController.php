<?php

namespace app\modules\posts\commands;

use Yii;

class ConsoleController extends \app\components\ConsoleController
{
    public function actionInstall()
    {
        $this->migrate(self::MIGRATE_UP, '@app/modules/posts/migrations');
    }
}