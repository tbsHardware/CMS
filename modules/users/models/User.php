<?php

namespace app\modules\users\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\modules\users\helpers\Password;

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

    public $password;

    protected $profile;

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

        $this->assignRole('user');

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
            $token = new Token(['type' => Token::TYPE_CONFIRMATION]);
            $token->link('user', $this);
        } else {
            $this->assignRole('user');
        }

        $this->sendMail(
            Yii::t('users', 'Registration on {0}', Yii::$app->name),
            'registration',
            [
                'password' => (Yii::$app->getModule('users')->sendPassword || !$this->password) ? $password : null,
                'token' => isset($token) ? $token : null,
            ]
        );

        return true;
    }

    public function assignRole($role)
    {
        $authManager = Yii::$app->getAuthManager();
        $role = $authManager->getRole($role);
        if ($role) {
            return $authManager->assign($role, $this->getId());
        }
        return false;
    }

    public function assignPermission($permission)
    {
        $authManager = Yii::$app->getAuthManager();
        $permission = $authManager->getPermission($permission);
        if ($permission) {
            return $authManager->assign($permission, $this->getId());
        }
        return false;
    }

    public function sendMail($subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $view = '@app/modules/users/mail/views/' . $view;
        $mailer->getView()->theme = Yii::$app->view->theme;

        return $mailer->compose(['html' => $view], $params)
            ->setTo($this->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject($subject)
            ->send();
    }

    public function changePassword($password)
    {
        return (bool)$this->updateAttributes([
            'password_hash' => Password::hash($password),
            'auth_key' => Yii::$app->security->generateRandomString(),
        ]);
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

    public function afterDelete()
    {
        parent::afterDelete();

        $authManager = Yii::$app->getAuthManager();
        $authManager->revokeAll($this->id);
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
        throw new \yii\base\NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
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

    public function getUserField()
    {
        return $this->hasMany(UserField::className(), ['user_id' => 'id']);
    }

    public function getProfile()
    {
        if ($this->profile === NULL) {

            if (Yii::$app->user->isGuest) {
                $visible = Field::VISIBLE_ALL;
            } elseif (Yii::$app->user->id === $this->id) {
                $visible = Field::VISIBLE_ONLY_OWNER;
            } else {
                $visible = Field::VISIBLE_REGISTER_USER;
            }

            return (new \yii\db\Query())
                ->select(['title', 'value'])
                ->from(self::tableName() . ' as t1')
                ->innerJoin(UserField::tableName() . ' as t2' , 't2.user_id = t1.id')
                ->innerJoin(Field::tableName() . ' as t3' , 't3.id = t2.field_id')
                ->where('t1.id = :id and t3.visible >= :vis', [':id' => $this->id, ':vis' => $visible])
                ->all();
        }

        return $this->profile;
    }

    public static function find()
    {
        return new \app\modules\users\models\query\UserQuery(get_called_class());
    }
}