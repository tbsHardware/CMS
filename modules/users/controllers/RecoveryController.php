<?php

namespace app\modules\users\controllers;

use app\modules\users\models\form\RecoveryForm;
use app\modules\users\models\Token;
use Yii;
use yii\web\Controller;

class RecoveryController extends Controller
{
    public function actionIndex()
    {
        $model = new RecoveryForm(['scenario' => RecoveryForm::SCENARIO_REQUEST]);

        if ($model->load(Yii::$app->request->post()) && $model->sendRecoveryMessage()) {

            return $this->render('/message', [
                'title' => Yii::t('users', 'Password recovery'),
                'message' => Yii::t('users', 'An email has been sent with instructions for resetting your password'),
            ]);
        }

        return $this->render('/recovery', [
            'model' => $model,
        ]);
    }

    public function actionReset($code)
    {
        $token = Token::find()->byCode($code)->byType(Token::TYPE_RECOVERY)->one();

        if ($token === null || $token->isExpired || !$token->user) {
            $message = Yii::t('users', 'The confirmation link is invalid or expired, please try requesting a new one');
        } else {

            $model = new RecoveryForm(['scenario' => RecoveryForm::SCENARIO_RESET]);

            if ($model->load(Yii::$app->getRequest()->post()) && $model->resetPassword($token->user)) {

                $token->delete();
                if (Yii::$app->user->isGuest) {
                    Yii::$app->user->login($token->user, Yii::$app->getModule('users')->rememberMe);
                }
                $message = Yii::t('users', 'The password was successfully changed, you can continue to work with the system');

            } else {
                return $this->render('/reset', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('/message', [
            'title' => Yii::t('users', 'Password recovery'),
            'message' => $message,
        ]);
    }
}