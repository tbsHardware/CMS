<?php

namespace app\modules\users\commands;

use Yii;

class ConsoleController extends \app\components\ConsoleController
{
    public function actionInstall()
    {
        $this->migrate(self::MIGRATE_UP, '@app/modules/users/migrations');
    }
}