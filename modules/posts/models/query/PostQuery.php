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
}