<?php

namespace app\modules\admin\commands;

use Yii;
use yii\console\Controller;

class ConsoleController extends Controller
{
    public function actionInstall()
    {
        $authManager = Yii::$app->getAuthManager();

        if (!$authManager->getPermission('admin_panel')) {

            $panel = $authManager->createPermission('admin_panel');
            $panel->description = 'Доступ в панель управления';

            $authManager->add($panel);

            $moder = $authManager->getRole('moder');
            $authManager->addChild($moder, $panel);

        }

        if (!$authManager->getPermission('admin_rbac')) {

            $rbac = $authManager->createPermission('admin_rbac');
            $rbac->description = 'Контроль доступа';

            $authManager->add($rbac);

            $admin = $authManager->getRole('admin');
            $authManager->addChild($admin, $rbac);
        }

    }

    public function actionUninstall()
    {
        $authManager = Yii::$app->getAuthManager();

        if ($panel=$authManager->getPermission('admin_panel')) {
            $authManager->remove($panel);
        }
        if ($rbac=$authManager->getPermission('admin_rbac')) {
            $authManager->remove($rbac);
        }
    }
}