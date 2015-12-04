<?php

namespace app\modules\post\commands;

use Yii;

class ConsoleController extends \app\components\ConsoleController
{
    const MIGRATION_PATH = '@app/modules/post/migrations';

    public function actionInstall()
    {
        $this->migrate(self::MIGRATE_UP, self::MIGRATION_PATH);
    }

    public function actionUninstall()
    {
        $this->migrate(self::MIGRATE_DOWN, self::MIGRATION_PATH);
    }
}