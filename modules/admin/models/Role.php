<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;

class Role extends Model
{
    public function search($params)
    {
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => Yii::$app->authManager->getRoles(),
            'sort' => [
                'attributes' => ['name', 'description'],
            ],
        ]);

        return $dataProvider;
    }
}