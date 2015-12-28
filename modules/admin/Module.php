<?php

namespace app\modules\admin;

use Yii;
use app\components\BaseModule;
use yii\base\Theme;
use yii\web\ForbiddenHttpException;

class Module extends BaseModule
{
    public $defaultRoute = 'dashboard/index';

    public $layout = 'admin';

    public $controllerNamespace = 'app\modules\admin\controllers';

    public $unmanagedModules = ['gii', 'debug', 'admin'];

    public $pathMap = [];

    private $_managedModules;

    public $controlMenu = [
        [
            'label' => 'Панель управления',
            'icon' => 'home',
            'action' => 'dashboard/index',
        ],
        [
            'label' => 'Контроль доступа',
            'role' => 'admin_rbac',
            'icon' => 'lock',
            'items' => [
                [
                    'label' => 'Все роли',
                    'action' => 'access/index',
                ],
                [
                    'label' => 'Добавить новую',
                    'action' => 'access/add',
                ],
            ]
        ],
    ];

    public function init()
    {
        $user = Yii::$app->getUser();
        if ($user->isGuest) {
            Yii::$app->response->redirect($user->loginUrl);
        } elseif (!$user->can('admin_panel')) {
            throw new ForbiddenHttpException();
        }

        parent::init();

        Yii::$app->errorHandler->errorAction = '/admin/dashboard/error';

        foreach ($this->getManagedModules() as $id => $module) {
            $this->controllerMap[$id] = ['class' => $module->controllerNamespace . '\AdminController'];
            $this->pathMap['@app/modules/admin/views/' . $id] = '@app/modules/' . $id . '/views/admin';
        }

        Yii::$app->view->theme = new Theme(['pathMap' => $this->pathMap]);
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