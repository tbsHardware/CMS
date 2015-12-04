<?php

namespace app\modules\admin;

use Yii;
use yii\base\Module as BaseModule;
use yii\base\Theme;

class Module extends BaseModule
{
    public $defaultRoute = 'dashboard/index';

    public $controllerNamespace = 'app\modules\admin\controllers';

    public $unmanagedModules = ['gii', 'debug', 'admin'];

    private $_managedModules;

    public function init()
    {
        parent::init();

        Yii::$app->view->theme = new Theme([
            'pathMap' => ['@app/views' => '@app/themes/admin/views'],
            'baseUrl' => '@app/themes/admin',
        ]);

        foreach ($this->getManagedModules() as $id => $module) {
            $this->controllerMap[$id] = ['class' => $module->controllerNamespace . '\AdminController'];
        }
    }

    public function getManagedModules()
    {
        if ($this->_managedModules === null) {
            $this->_managedModules = [];
            foreach (Yii::$app->getModules() as $id => $module) {
                if (!in_array($id, $this->unmanagedModules)) {
                    if (file_exists(Yii::$app->basePath . '/modules/' . $id . '/controllers/AdminController.php')) {
                        $this->_managedModules[$id] = $module;
                    }
                }
            }
        }
        return $this->_managedModules;
    }
}