<?php

namespace app\modules\posts\controllers;

use Yii;
use yii\web\Controller;

class PostController extends Controller
{
    public function actionIndex($id)
    {
        var_dump($id);
    }
}