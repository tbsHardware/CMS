<?php

namespace app\modules\users\controllers;

use app\modules\users\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class LogoutController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    ['allow' => true, 'actions' => ['index'], 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->trigger(User::BEFORE_LOGOUT);

        Yii::$app->user->logout();

        $this->trigger(User::AFTER_LOGOUT);

        return $this->goHome();
    }
}