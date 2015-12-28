<?php

namespace app\modules\post\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\post\models\Menu]].
 *
 * @see \app\modules\post\models\Menu
 */
class MenuQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\post\models\Menu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\post\models\Menu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
