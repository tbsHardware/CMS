<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class ModuleController extends Controller
{

    public function actionIndex($module, $action = 'index')
    {
        if (!file_exists(Yii::$app->basePath . '/modules/' . $module . '/commands/ConsoleController.php')) {
            $this->stdout("Module or ConsoleController does not exist\n", Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        Yii::$app->controllerMap[$module] = [
            'class' => 'app\modules\\' . $module . '\commands\ConsoleController',
        ];

        Yii::$app->runAction($module . '/' . $action);
        $this->stdout("$module/$action is completed\n", Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }
}
