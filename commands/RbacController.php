<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\components\Core;

class RbacController extends Controller
{
    public function actionInstall()
    {
        $authManager = Yii::$app->getAuthManager();
        if($authManager === NULL) {
            $this->stdout("AuthManager does not exist\n", Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        Core::migrate('up', '@yii/rbac/migrations');

        $authManager->removeAll();

        $user = $authManager->createRole('user');
        $moder = $authManager->createRole('moder');
        $admin = $authManager->createRole('admin');

        $user->description = 'Пользователь';
        $moder->description = 'Модератор';
        $admin->description = 'Администратор';

        $authManager->add($user);
        $authManager->add($moder);
        $authManager->add($admin);

        $authManager->addChild($moder, $user);
        $authManager->addChild($admin, $moder);

        $this->stdout("RBAC is installed\n", Console::FG_GREEN);
        return Controller::EXIT_CODE_NORMAL;
    }
}