<?php

namespace app\modules\users;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;

class Module extends BaseModule implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\users\controllers';

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\users\commands\ConsoleController';
        } else {
            Yii::$container->set('app\components\WebUser', [
                'enableAutoLogin' => true,
                'loginUrl' => ['/user/login'],
                'logoutUrl' => ['/user/logout'],
                'profileUrl' => ['/user/profile'],
                'identityClass' => 'app\modules\users\models\User',
            ]);
        }
    }
}