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

        $view = $authManager->createPermission('users_view');
        $update = $authManager->createPermission('users_update');
        $delete = $authManager->createPermission('users_delete');

        $view->description = 'Просмотр профиля';
        $update->description = 'Редактирование пользователя';
        $delete->description = 'Удаление пользователя';

        $authManager->add($view);
        $authManager->add($update);
        $authManager->add($delete);

        $user = $authManager->getRole('user');
        $moder = $authManager->getRole('moder');
        $admin = $authManager->getRole('admin');

        $authManager->addChild($user, $view);
        $authManager->addChild($moder, $update);
        $authManager->addChild($admin, $delete);
    }

    public function actionUninstall()
    {
        //Core::migrate('down', '@users/migrations');

        $authManager = Yii::$app->getAuthManager();

        $authManager->remove($authManager->getPermission('users_profile'));
        $authManager->remove($authManager->getPermission('users_edit'));
        $authManager->remove($authManager->getPermission('users_delete'));
    }
}