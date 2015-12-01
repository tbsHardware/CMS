<?php

namespace app\components;

use yii\db\ActiveQuery as YiiActiveQuery;

class ActiveQuery extends YiiActiveQuery
{

    public function byId($id)
    {
        return $this->andWhere(['id' => $id]);
    }
}