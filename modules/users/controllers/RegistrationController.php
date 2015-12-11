<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\users\models\form\RegistrationForm;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        $module = Yii::$app->getModule('users');

        if (!$module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        $model = new RegistrationForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {

            if($module->enableConfirmation) {
                $message = Yii::t('users', 'Your account has been created and a message with further instructions has been sent to your email');
            } else {
                Yii::$app->user->login($model->user, $module->rememberMe);
                $message = Yii::t('users', 'Your account has been created, you can already start using the system');
            }

            return $this->render('/message', [
                'title'  => Yii::t('users', 'Registration'),
                'message' => $message,
            ]);
        }

        return $this->render('/registration', [
            'model' => $model,
            'module' => $module,
        ]);
    }
}