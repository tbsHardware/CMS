<?php

namespace app\modules\users;

use Yii;
use yii\base\Module as BaseModule;
use yii\base\BootstrapInterface;

class Module extends BaseModule implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Yii::$container->set('yii\web\User', [
            'enableAutoLogin' => true,
            'loginUrl'        => ['/user/login'],
            'identityClass'   => 'app\modules\users\models\User',
        ]);
    }
}