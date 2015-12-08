<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class ServiceController extends Controller
{
    public function actionClear()
    {
        $assetsPath = Yii::getAlias("@webroot") . '/assets';
        $it = new \RecursiveDirectoryIterator($assetsPath, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } elseif ($file->getPath() !== $assetsPath) {
                unlink($file->getRealPath());
            }
        }
        $this->stdout("Assets successfully cleared\n", Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }
}