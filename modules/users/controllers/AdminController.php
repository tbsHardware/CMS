<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;

class AdminController extends Controller
{
    //public $layout = 'main';

    public function actionIndex()
    {
      return $this->render('index');
    }
}

