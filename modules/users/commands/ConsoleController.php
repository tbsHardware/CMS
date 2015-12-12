<?php

namespace app\modules\users\commands;

use Yii;
use yii\console\Controller;
use app\components\Core;

class ConsoleController extends Controller
{
    public function actionInstall()
    {
        Core::migrate('up', '@users/migrations');

        $authManager = Yii::$app->getAuthManager();

        $profile = $authManager->createPermission('users_profile');
        $edit = $authManager->createPermission('users_edit');
        $delete = $authManager->createPermission('users_delete');

        $profile->description = 'Просмотр профиля';
        $edit->description = 'Редактирование пользователя';
        $delete->description = 'Удаление пользователя';

        $authManager->add($profile);
        $authManager->add($edit);
        $authManager->add($delete);

        $user = $authManager->getRole('user');
        $moder = $authManager->getRole('moder');
        $admin = $authManager->getRole('admin');

        $authManager->addChild($user, $profile);
        $authManager->addChild($moder, $edit);
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