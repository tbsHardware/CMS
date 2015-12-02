<?php

namespace app\modules\users\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $unconfirmed_email
 * @property string $registration_ip
 * @property integer $confirmed_at
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Token[] $tokens
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'auth_key', 'registration_ip', 'created_at', 'updated_at'], 'required'],
            [['confirmed_at', 'blocked_at', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'unconfirmed_email'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['email'], 'unique']
        ];
    }

    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return $this->getAttribute('confirmed_at') != null;
    }

    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->getAttribute('blocked_at') != null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }

    public function createUser($confirm = null)
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        if ($confirm === null)
            $confirm = Yii::$app->params['enableConfirmation'];

        $this->confirmed_at = $confirm ? null : time();

        if (!$this->save()) {
            return false;
        }

        return true;
    }

    /* IdentityInterface */
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /** @inheritdoc */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /** @inheritdoc */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /** @inheritdoc */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'unconfirmed_email' => 'Unconfirmed Email',
            'registration_ip' => 'Registration Ip',
            'confirmed_at' => 'Confirmed At',
            'blocked_at' => 'Blocked At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }
}
