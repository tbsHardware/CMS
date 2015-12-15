<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\users\models\form\RegistrationForm;
use app\modules\users\models\Token;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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
                $message = Yii::t('users', 'Thank you, registration is now complete, you can already start using the system');
            }

            return $this->render('/message', [
                'title'  => Yii::t('users', 'Registration'),
                'message' => $message,
            ]);
        }

        return $this->render('/registration', [
            'model' => $model,
        ]);
    }

    public function actionConfirm($code)
    {
        $token = Token::find()->byCode($code)->byType(Token::TYPE_CONFIRMATION)->one();

        if ($token === NULL || $token->isExpired) {
            $message = Yii::t('users', 'The confirmation link is invalid or expired, please try requesting a new one');
        } else {

            $token->delete();
            if ($token->user->confirm()) {

                if (Yii::$app->user->isGuest) {
                    Yii::$app->user->login($token->user, Yii::$app->getModule('users')->rememberMe);
                }

                $message = Yii::t('users', 'Thank you, registration is now complete, you can already start using the system');
            } else {
                $message = Yii::t('users', 'Something went wrong and your account has not been confirmed');
            }
        }

        return $this->render('/message', [
            'title'  => Yii::t('users', 'Activation of account'),
            'message' => $message,
        ]);
    }
}