<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;
use app\modules\users\models\Token;

class ConfirmController extends Controller
{
    public function actionIndex($code)
    {
        $token = Token::find()->byCode($code)->byType(Token::TYPE_CONFIRMATION)->one();

        if ($token === NULL || $token->isExpired) {
            $message = Yii::t('users', 'The confirmation link is invalid or expired, please try requesting a new one');
        } else {
            $token->delete();
            if ($token->user->confirm()) {
                Yii::$app->user->login($token->user, Yii::$app->getModule('users')->rememberMe);
                $message = Yii::t('users', 'Thank you, registration is now complete');
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