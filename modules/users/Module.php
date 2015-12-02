<?php

namespace app\modules\users;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;

class Module extends BaseModule implements BootstrapInterface
{
    public $migrationPath = 'app/modules/users/migrations';

    public $commandsPath = 'app/modules/users/commands';

    public function bootstrap($app)
    {
        Yii::$container->set('yii\web\User', [
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/login'],
            'identityClass' => 'app\modules\users\models\User',
        ]);
    }
}