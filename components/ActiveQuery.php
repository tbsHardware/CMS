<?php

namespace app\components;

class ActiveQuery extends \yii\db\ActiveQuery
{

    public function byId($id)
    {
        return $this->andWhere(['id' => $id]);
    }
}