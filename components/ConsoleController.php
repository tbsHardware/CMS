<?php

namespace app\components;

use Yii;
use yii\console\controllers\MigrateController;

class ConsoleController extends \yii\console\Controller
{
    const MIGRATE_UP = 'up';
    const MIGRATE_DOWN = 'down';

    public function migrate($action, $path, $params = [])
    {
        $params['migrationPath'] = $path;
        $params['interactive'] = false;

        $migration = new MigrateController('migrate', Yii::$app);
        $migration->runAction($action, $params);
    }
}