<?php

namespace app\modules\users\controllers;

use app\modules\users\models\User;
use app\modules\users\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['actions' => ['index'], 'allow' => true, 'roles' => ['users_access']],
                    ['actions' => ['add'], 'allow' => true, 'roles' => ['users_add']],
                    ['actions' => ['update'], 'allow' => true, 'roles' => ['users_update']],
                    ['actions' => ['confirm'], 'allow' => true, 'roles' => ['users_confirm']],
                    ['actions' => ['block'], 'allow' => true, 'roles' => ['users_block']],
                    ['actions' => ['unblock'], 'allow' => true, 'roles' => ['users_unblock']],
                    ['actions' => ['delete'], 'allow' => true, 'roles' => ['users_delete']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'confirm' => ['post'],
                    'block' => ['post'],
                    'unblock' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    public function actionAdd()
    {

    }

    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->getUser($id);
    }

    public function actionConfirm($id)
    {
        $user = $this->getUser($id);


    }

    public function actionBlock($id)
    {
        if ($id == Yii::$app->user->id) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'You can not block your own account'));
        } else {

            $user = $this->getUser($id);
            if ($user->isBlocked) {
                throw new BadRequestHttpException();
            }

            $user->block();
            Yii::$app->getSession()->setFlash('success', Yii::t('users', 'User has been blocked'));
        }

        return $this->redirect(Url::previous('actions-redirect'));
    }

    public function actionUnblock($id)
    {
        $user = $this->getUser($id);
        if (!$user->isBlocked) {
            throw new BadRequestHttpException();
        }

        $user->unblock();
        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'User has been unblocked'));

        return $this->redirect(Url::previous('actions-redirect'));
    }

    public function actionDelete($id)
    {
        $user = $this->getUser($id);
    }

    /**
     * @return User
     * @throws NotFoundHttpException
     */
    private function getUser($id)
    {
        $user = User::find()->byId($id)->one();
        if ($user === null) {
            throw new NotFoundHttpException();
        }
        return $user;
    }

}

