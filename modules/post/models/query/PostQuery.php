<?php

namespace app\modules\post\models\query;

use Yii;
use app\components\ActiveQuery;
use app\modules\post\models\Post;

class PostQuery extends ActiveQuery
{
    public function published()
    {
        return $this->andWhere(['post_status' => Post::STATUS_PUBLISHED]);
    }
}