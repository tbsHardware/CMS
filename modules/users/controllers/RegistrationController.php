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

            return $this->render('/message', [
                'title'  => Yii::t('users', 'Your account has been created'),
                'module' => $this->module,
            ]);
        }

        return $this->render('/registration', [
            'model' => $model,
            'module' => $module,
        ]);
    }
}