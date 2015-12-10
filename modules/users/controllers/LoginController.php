<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;
use app\modules\users\models\User;
use app\modules\users\models\LoginForm;

class LoginController extends Controller
{
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $this->trigger(User::BEFORE_LOGIN);

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->trigger(User::AFTER_LOGIN);
            return $this->goBack();
        }

        return $this->render('/login', [
            'model' => $model,
        ]);
    }
}