<?php

namespace app\modules\post\controllers;

use app\modules\post\models\Page;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;

class PageController extends Controller
{
 /*   public function actionIndex($page, $parent = null)
    {
        $path = $parent ? ($parent . '/' . $page) : $page;
        $path = preg_replace('#/$#', '', $path);

        $page = Page::find()->byPath($path)->one();
        if (!$page) {
            throw new HttpException(404, '�������� �� �������');
        }

        return $this->render($page->page_template, [
            'page' => $page,
        ]);
    }*/
}