<?php

namespace app\commands;


use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\components\Core;

class ClearController extends Controller
{
    public function actionAssets()
    {
        Core::clearAssets();
        $this->stdout("Assets successfully cleared\n", Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }
}