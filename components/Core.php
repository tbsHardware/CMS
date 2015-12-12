<?php

namespace app\components;

use Yii;
use yii\console\controllers\MigrateController;

class Core extends \yii\base\Component
{
    public static function clearAssets()
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
    }

    public static function migrate($action, $path, $params = [])
    {
        $params['migrationPath'] = $path;
        $params['interactive'] = false;

        $migration = new MigrateController('migrate', Yii::$app);
        $migration->runAction($action, $params);
    }
}