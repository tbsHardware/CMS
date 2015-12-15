<?php

namespace app\modules\users\models\form;

use app\modules\users\models\User;
use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $email;
    public $username;
    public $password;
    public $passwordRepeat;
    public $reCaptcha;

    protected $user;

    public static $usernameRegexp = '/^[-a-zA-Z0-9_\.@]+$/';

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'registration-form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username rules
            'usernameRequired' => ['username', 'required'],
            'usernameLength' => ['username', 'string', 'min' => 4, 'max' => 16],
            'usernameTrim' => ['username', 'filter', 'filter' => 'trim'],
            'usernamePattern' => ['username', 'match', 'pattern' => self::$usernameRegexp],
            'usernameUnique' => [
                'username',
                'unique',
                'targetClass' => User::className(),
                'message' => Yii::t('users', 'This username has already been taken')
            ],
            // email rules
            'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailPattern' => ['email', 'email'],
            'emailUnique' => [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => Yii::t('users', 'This email address has already been taken')
            ],
            // password rules
            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'min' => 6],
            // passwordRepeat rules
            'passwordRepeatRequired' => ['passwordRepeat', 'required'],
            'passwordRepeatCompare' => ['passwordRepeat', 'compare', 'compareAttribute' => 'password',
                'message' => Yii::t('users', "Passwords don't match"),
            ],
            // captcha rules
            'captchaValidator' => ['reCaptcha', \himiklab\yii2\recaptcha\ReCaptchaValidator::className()],
        ];
    }

    public function register()
    {
        if ($this->validate()) {

            $this->user = new User([
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
            ]);

            return $this->user->register();
        }

        return false;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('users', 'Username'),
            'password' => Yii::t('users', 'Password'),
            'passwordRepeat' => Yii::t('users', 'Repeat password'),
            'reCaptcha' => Yii::t('users', 'Captcha'),
        ];
    }
}