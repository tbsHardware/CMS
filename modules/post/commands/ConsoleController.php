<?php

namespace app\modules\post\commands;

use Yii;
use yii\console\Controller;
use app\components\Core;

class ConsoleController extends Controller
{
    public $migrationsPath = '@app/modules/post/migrations';

    public function actionInstall()
    {
        Core::migrate('up', $this->migrationsPath);
    }

    public function actionUninstall()
    {
        Core::migrate('down', $this->migrationsPath);
    }
}