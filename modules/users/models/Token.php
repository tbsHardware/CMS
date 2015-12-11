<?php

namespace app\modules\users\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\users\models\query\TokenQuery;

/**
 * This is the model class for table "users_token".
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $created_at
 * @property integer $type
 *
 * @property User $user
 */
class Token extends ActiveRecord
{
    const TYPE_CONFIRMATION      = 0;
    const TYPE_RECOVERY          = 1;
    const TYPE_CONFIRM_NEW_EMAIL = 2;
    const TYPE_CONFIRM_OLD_EMAIL = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_token}}';
    }

    /**
     * @return bool Whether token has expired.
     */
    public function getIsExpired()
    {
        $module = Yii::$app->getModule('users');

        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $expirationTime = $module->confirmWithin;
                break;
            case self::TYPE_RECOVERY:
                $expirationTime = $module->recoverWithin;
                break;
            default:
                throw new \RuntimeException();
        }

        return ($this->created_at + $expirationTime) < time();
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            static::deleteAll(['user_id' => $this->user_id, 'type' => $this->type]);
            $this->setAttribute('created_at', time());
            $this->setAttribute('code', Yii::$app->security->generateRandomString());
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            // Тут надо бы отправить на email пользователю ссылку
            // Сделать что то типо switch по type, использовать разные шаблоны для писем
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function find()
    {
        return new TokenQuery(get_called_class());
    }
}
