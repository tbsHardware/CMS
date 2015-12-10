<?php

namespace app\modules\users\models\query;

use app\components\ActiveQuery;

class UserQuery extends ActiveQuery
{
    public function byEmail($email)
    {
        return $this->andWhere(['email' => $email]);
    }

    public function byUsername($username)
    {
        return $this->andWhere(['username' => $username]);
    }
}