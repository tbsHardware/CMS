<?php

namespace app\modules\users\controllers;

use app\modules\users\models\User;
use Yii;
use yii\web\Controller;

class LogoutController extends Controller
{
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $this->trigger(User::BEFORE_LOGOUT);
            Yii::$app->user->logout();
            $this->trigger(User::AFTER_LOGOUT);
        }

        return $this->goHome();
    }
}