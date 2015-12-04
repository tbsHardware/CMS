<?php

namespace app\modules\post\controllers;

use app\modules\post\models\Post;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;

class PostController extends Controller
{
    public function actionIndex($postId, $postAlias, $page, $parent = null)
    {
        $post = Post::find()->byId($postId)->one();
        if (!$post) {
            throw new HttpException(404, 'Страница не найдена');
        }

        $path = ($parent ? ($parent . '/') : '') . $page . '/' . $postId . '/' . $postAlias;
        $path = preg_replace('#/$#', '', $path);

        if ($post->post_path !== $path) {
            throw new HttpException(404, 'Страница не найдена');
        }

        switch ($post->post_status) {
            case Post::STATUS_DRAFT:
                /*$user = Yii::$app->getUser();
                if (!$user->isAdmin() && $user->getId() !== $post->post_author)
                    throw new HttpException(404, 'Страница не найдена');*/
                break;
            case Post::STATUS_USER_ONLY:
                Yii::$app->session->setFlash('UserOnly');
                Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
                break;
        }

        return $this->render('post', [
            'post' => $post
        ]);
    }
}