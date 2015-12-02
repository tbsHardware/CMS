<?php

namespace app\modules\posts\controllers;

use Yii;
use yii\web\Controller;

class PageController extends Controller
{
    public function actionIndex($page)
    {
        var_dump($page);
    }
}