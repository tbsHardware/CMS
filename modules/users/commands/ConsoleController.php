<?php

namespace app\modules\users\commands;

use Yii;
use yii\console\Controller;
use app\components\Core;

class ConsoleController extends Controller
{
    public function actionInstall()
    {
        Core::migrate('up', '@app/modules/users/migrations');

        $authManager = Yii::$app->getAuthManager();

        $user = $authManager->getRole('user');
        $moder = $authManager->getRole('moder');
        $admin = $authManager->getRole('admin');

        if (!$authManager->getPermission('users_view')) {

            $view = $authManager->createPermission('users_view');
            $view->description = 'Просмотр профиля';

            $authManager->add($view);
            $authManager->addChild($user, $view);
        }

        if (!$authManager->getPermission('users_access')) {

            $access = $authManager->createPermission('users_access');
            $access->description = 'Доступ к пользователям';

            $authManager->add($access);
            $authManager->addChild($moder, $access);
        }

        if (!$authManager->getPermission('users_add')) {

            $add = $authManager->createPermission('users_add');
            $add->description = 'Добавление пользователя';

            $authManager->add($add);
            $authManager->addChild($moder, $add);
        }

        if (!$authManager->getPermission('users_update')) {

            $update = $authManager->createPermission('users_update');
            $update->description = 'Редактирование пользователя';

            $authManager->add($update);
            $authManager->addChild($moder, $update);
        }

        if (!$authManager->getPermission('users_delete')) {

            $delete = $authManager->createPermission('users_delete');
            $delete->description = 'Удаление пользователя';

            $authManager->add($delete);
            $authManager->addChild($admin, $delete);
        }
    }

    public function actionUninstall()
    {
        //Core::migrate('down', '@users/migrations');

        $authManager = Yii::$app->getAuthManager();

        if ($view=$authManager->getPermission('users_view')) {
            $authManager->remove($view);
        }
        if ($access=$authManager->getPermission('users_access')) {
            $authManager->remove($access);
        }
        if ($add=$authManager->getPermission('users_add')) {
            $authManager->remove($add);
        }
        if ($update=$authManager->getPermission('users_update')) {
            $authManager->remove($update);
        }
        if ($delete=$authManager->getPermission('users_delete')) {
            $authManager->remove($delete);
        }
    }
}