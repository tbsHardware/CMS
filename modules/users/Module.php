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

    public $loginUrl = ['/users/login'];

    public $logoutUrl = ['/users/logout'];

    public $registrationUrl = ['/users/registration'];

    public $recoveryUrl = ['/users/recovery'];

    public $profileUrl = ['/users/profile'];

    public $rememberMe = 1209600; // two weeks

    public $confirmWithin = 86400; // 24 hours

    public $recoverWithin = 21600; // 6 hours

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\users\commands\ConsoleController';
        } else {
            Yii::$container->set('app\components\WebUser', [
                'enableAutoLogin' => true,
                'loginUrl' => $this->loginUrl,
                'logoutUrl' => $this->logoutUrl,
                'registrationUrl' => $this->registrationUrl,
                'recoveryUrl' => $this->recoveryUrl,
                'profileUrl' => $this->profileUrl,
                'identityClass' => 'app\modules\users\models\User',
            ]);
        }
    }
}