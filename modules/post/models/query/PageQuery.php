<?php

namespace app\modules\post\models\query;

use app\components\ActiveQuery;
use Yii;

class PageQuery extends ActiveQuery
{
    public function parent()
    {
        return $this->andWhere(['page_parent' => null]);
    }

    public function byPath($path)
    {
        return $this->andWhere(['page_path' => $path]);
    }
}