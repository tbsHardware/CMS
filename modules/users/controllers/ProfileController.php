<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\users\models\User;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['view'], 'roles' => ['users_view']],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('users_update') ||
                                Yii::$app->request->get('id') == Yii::$app->user->id;
                        }
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index', [
            'profile' => Yii::$app->user->identity->profile,
        ]);
    }

    public function actionView($id)
    {
        $user = User::find()->byId($id)->one();
        if ($user === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('index', [
            'profile' => $user->profile,
        ]);
    }

    public function actionUpdate($id = null)
    {
        $id = $id ? $id : Yii::$app->user->id;

        $user = User::find()->byId($id)->one();
        if ($user === null) {
            throw new NotFoundHttpException();
        }
    }
}