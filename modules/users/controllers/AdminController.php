<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;

class AdminController extends Controller
{
    public function actionIndex()
    {
       $this->render('index');
    }
}

