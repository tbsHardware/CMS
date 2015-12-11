<?php

namespace app\modules\users\models;


use app\modules\users\helpers\Password;
use app\modules\users\models\query\UserQuery;
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
 * @property Profile[] $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const BEFORE_CREATE = 'beforeCreate';
    const AFTER_CREATE = 'afterCreate';
    const BEFORE_REGISTER = 'beforeRegister';
    const AFTER_REGISTER = 'afterRegister';
    const BEFORE_LOGIN = 'beforeLogin';
    const AFTER_LOGIN = 'afterLogin';
    const BEFORE_LOGOUT = 'beforeLogout';
    const AFTER_LOGOUT = 'afterLogout';
    const BEFORE_CONFIRM = 'beforeConfirm';
    const AFTER_CONFIRM = 'afterConfirm';

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    public $password;

    public static $usernameRegexp = '/^[-a-zA-Z0-9_\.@]+$/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_user}}';
    }

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
    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['username', 'password'],
            self::SCENARIO_REGISTER => ['username', 'email', 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username rules
            'usernameRequired' => ['username', 'required', 'on' => [self::SCENARIO_LOGIN, self::SCENARIO_REGISTER]],
            'usernameMatch' => ['username', 'match', 'pattern' => static::$usernameRegexp],
            'usernameLength' => ['username', 'string', 'min' => 4, 'max' => 16],
            'usernameUnique' => [
                'username',
                'unique',
                'message' => Yii::t('users', 'This username has already been taken')
            ],
            'usernameTrim' => ['username', 'trim'],
            // email rules
            'emailRequired' => ['email', 'required', 'on' => [self::SCENARIO_REGISTER]],
            'emailPattern' => ['email', 'email'],
            'emailLength' => ['email', 'string', 'max' => 255],
            'emailUnique' => [
                'email',
                'unique',
                'message' => Yii::t('user', 'This email address has already been taken')
            ],
            'emailTrim' => ['email', 'trim'],
            // password rules
            'passwordRequired' => ['password', 'required', 'on' => [self::SCENARIO_LOGIN, self::SCENARIO_REGISTER]],
            'passwordLength' => [
                'password',
                'string',
                'min' => 6,
                'on' => [self::SCENARIO_LOGIN, self::SCENARIO_REGISTER]
            ],
        ];
    }

    public function register()
    {
        $this->trigger(self::BEFORE_REGISTER);

        $enableConfirmation = Yii::$app->getModule('users')->enableConfirmation;
        if (!$this->add($enableConfirmation)) {
            return false;
        }

        $this->trigger(self::AFTER_REGISTER);

        return true;
    }

    public function create()
    {
        $this->trigger(self::BEFORE_CREATE);

        if (!$this->add(false)) {
            return false;
        }

        $this->trigger(self::AFTER_CREATE);

        return true;
    }

    public function confirm()
    {
        $this->trigger(self::BEFORE_CONFIRM);

        if (!(bool)$this->updateAttributes(['confirmed_at' => time()])) {
            return false;
        }

        $this->trigger(self::AFTER_CONFIRM);

        return true;
    }

    protected function add($confirmationRequired)
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $password = $this->password ? $this->password : Password::generate(8);
        $this->password_hash = Password::hash($password);
        $this->auth_key = Yii::$app->security->generateRandomString();
        $this->confirmed_at = $confirmationRequired ? null : time();

        if (Yii::$app instanceof \yii\web\Application) {
            $this->registration_ip = Yii::$app->request->userIP;
        }

        if (!$this->save()) {
            return false;
        }

        if ($confirmationRequired) {
            $token = Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
            $token->link('user', $this);
        }

        return true;
    }

    /**
     * Blocks the user by setting 'blocked_at' field to current time and regenerates auth_key.
     */
    public function block()
    {
        return (bool)$this->updateAttributes([
            'blocked_at' => time(),
            'auth_key' => Yii::$app->security->generateRandomString(),
        ]);
    }

    /**
     * UnBlocks the user by setting 'blocked_at' field to null.
     */
    public function unblock()
    {
        return (bool)$this->updateAttributes(['blocked_at' => null]);
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

    /* IdentityInterface */
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

    /**
     * @return bool Whether the user is an admin or not.
     */
    public function getIsAdmin()
    {
        //return in_array($this->username, $this->module->admins);
        return true;
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
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasMany(Profile::className(), ['user_id' => 'id']);
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
