<?php

namespace app\modules\users\commands;

use app\components\Core;
use Yii;
use yii\console\Controller;

class ConsoleController extends Controller
{
    private $_permissions = [
        'users_view' => ['description' => 'Просмотр профиля', 'role' => 'user'],
        'users_access' => ['description' => 'Доступ к пользователям', 'role' => 'moder'],
        'users_confirm' => ['description' => 'Активация пользователя', 'role' => 'moder'],
        'users_block' => ['description' => 'Блокирование пользователя', 'role' => 'moder'],
        'users_unblock' => ['description' => 'Разблокирование пользователя', 'role' => 'moder'],
        'users_add' => ['description' => 'Добавление пользователя', 'role' => 'admin'],
        'users_update' => ['description' => 'Редактирование пользователя', 'role' => 'admin'],
        'users_delete' => ['description' => 'Удаление пользователя', 'role' => 'admin'],
    ];

    public function actionInstall()
    {
        Core::migrate('up', '@app/modules/users/migrations');

        $authManager = Yii::$app->getAuthManager();

        $roles = [
            'user' => $authManager->getRole('user'),
            'moder' => $authManager->getRole('moder'),
            'admin' => $authManager->getRole('admin'),
        ];

        foreach ($this->_permissions as $id => $permission) {

            if (!$authManager->getPermission($id)) {

                $view = $authManager->createPermission($id);
                $view->description = $permission['description'];

                $authManager->add($view);
                $authManager->addChild($roles[$permission['role']], $view);
            }
        }
    }

    public function actionUninstall()
    {
        //Core::migrate('down', '@users/migrations');

        $authManager = Yii::$app->getAuthManager();

        foreach ($this->_permissions as $id => $permission) {

            $view = $authManager->getPermission($id);
            if ($view) {
                $authManager->remove($view);
            }
        }
    }
}