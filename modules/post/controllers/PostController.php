<?php

namespace app\modules\post\controllers;

use Yii;
use yii\web\Controller;

class PostController extends Controller
{
    public function actionIndex($parent, $child = null, $id = null)
    {
        var_dump([$parent, $child, $id]);
    }
}