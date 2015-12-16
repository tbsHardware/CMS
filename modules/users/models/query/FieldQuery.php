<?php

namespace app\modules\users\models\query;

use app\components\ActiveQuery;
use app\modules\users\models\Field;
use Yii;

class FieldQuery extends ActiveQuery
{
    public function forAll()
    {
        return $this->andWhere(['visible' => Field::VISIBLE_ALL])->addOrderBy('position');
    }

    public function forUser()
    {
        return $this->andWhere(['visible' => Field::VISIBLE_REGISTER_USER])->addOrderBy('position');
    }

    public function forOwner()
    {
        return $this->andWhere(['visible' => Field::VISIBLE_ONLY_OWNER])->addOrderBy('position');
    }

    public function sorted()
    {
        return $this->addOrderBy('position');
    }
}