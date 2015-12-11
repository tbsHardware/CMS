<?php

namespace app\modules\users\models\query;

use app\components\ActiveQuery;
use Yii;

class TokenQuery extends ActiveQuery
{
    public function byCode($code)
    {
        return $this->andWhere(['code' => $code]);
    }

    public function byUser($id)
    {
        return $this->andWhere(['user_id' => $id]);
    }

    public function byType($type)
    {
        return $this->andWhere(['type' => $type]);
    }
}