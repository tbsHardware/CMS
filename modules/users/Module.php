<?php

namespace app\modules\users;

use Yii;
use yii\base\BootstrapInterface;
use app\components\BaseModule;

class Module extends BaseModule implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\users\controllers';

    public $enableConfirmation = true;

    public $enableUnconfirmedLogin = false;

    public $rememberMe = 1209600; // two weeks

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\users\commands\ConsoleController';
        } else {
            Yii::$container->set('app\components\WebUser', [
                'enableAutoLogin' => true,
                'loginUrl' => ['/users/login'],
                'logoutUrl' => ['/users/logout'],
                'profileUrl' => ['/users/profile'],
                'identityClass' => 'app\modules\users\models\User',
            ]);
        }
    }
}