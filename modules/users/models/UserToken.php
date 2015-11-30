<?php

namespace app\modules\users\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_token".
 *
 * @property integer $user_id
 * @property string $token
 * @property integer $created_at
 * @property integer $type
 *
 * @property User $user
 */
class UserToken extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'created_at', 'type'], 'required'],
            [['user_id', 'created_at', 'type'], 'integer'],
            [['token'], 'string', 'max' => 32],
            [['user_id', 'token', 'type'], 'unique', 'targetAttribute' => ['user_id', 'token', 'type'],
                'message' => 'The combination of User ID, Token and Type has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'token' => 'Token',
            'created_at' => 'Created At',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
