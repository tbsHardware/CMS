<?php

namespace app\modules\users;

use Yii;
use yii\base\BootstrapInterface;
use app\components\BaseModule;

class Module extends BaseModule implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\users\controllers';

    public $enableRegistration = true;

    public $enableConfirmation = true;

    public $enableUnconfirmedLogin = false;

    public $sendPassword = false;

    public $rememberMe = 1209600; // two weeks

    public $confirmWithin = 86400; // 24 hours

    public $recoverWithin = 21600; // 6 hours

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\users\commands\ConsoleController';
        } else {
            Yii::$container->set('yii\web\User', [
                'enableAutoLogin' => true,
                'loginUrl' => ['/users/login'],
                'identityClass' => 'app\modules\users\models\User',
            ]);
        }
    }
}