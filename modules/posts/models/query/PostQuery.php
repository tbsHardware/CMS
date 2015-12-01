<?php

namespace app\modules\posts\models\query;

use Yii;
use app\components\ActiveQuery;
use app\modules\posts\models\Post;

class PostQuery extends ActiveQuery
{
    public function byPublished()
    {
        return $this->andWhere(['post_status' => Post::STATUS_PUBLISHED]);
    }

    public function byPage()
    {
        return $this->andWhere(['post_type' => Post::TYPE_PAGE]);
    }

    public function byPost()
    {
        return $this->andWhere(['post_type' => Post::TYPE_POST]);
    }

    public function byParent()
    {
        return $this->andWhere(['post_parent' => NULL]);
    }
}