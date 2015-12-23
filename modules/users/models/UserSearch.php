<?php

namespace app\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
    /** @var integer */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $email;

    /** @var int */
    public $created_at_from;

    /** @var int */
    public $created_at_to;

    /** @var string */
    public $registration_ip;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'email', 'registration_ip'], 'safe'],
            [['created_at_from', 'created_at_to'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $created_at_from = $this->created_at_from ? strtotime($this->created_at_from . ' 00:00:00') : null;
        $created_at_to = $this->created_at_to ? strtotime($this->created_at_to . ' 23:59:59') : null;

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['registration_ip' => $this->registration_ip])
            ->andFilterWhere(['>=', 'created_at', $created_at_from])
            ->andFilterWhere(['<=', 'created_at', $created_at_to]);

        return $dataProvider;
    }
}