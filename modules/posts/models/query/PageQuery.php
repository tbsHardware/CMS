<?php

namespace app\modules\posts\models\query;

use Yii;
use app\components\ActiveQuery;

class PageQuery extends ActiveQuery
{
    public function byParent()
    {
        return $this->andWhere(['page_parent' => NULL]);
    }
}