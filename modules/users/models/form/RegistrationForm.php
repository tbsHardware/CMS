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
    public $captcha;

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
            'usernamePattern' => ['username', 'match', 'pattern' => User::$usernameRegexp],
            'usernameUnique' => [
                'username',
                'unique',
                'targetClass' => User::className(),
                'message' => Yii::t('users', 'This username has already been taken')
            ],
            // email rules
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
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
            'captchaRequired' => ['captcha', 'required'],
            'captchaPattern' => ['captcha', 'captcha'],
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User(['scenario' => User::SCENARIO_REGISTER]);
        $user->setAttributes($this->attributes);

        if (!$user->register()) {
            return false;
        }

        Yii::$app->session->setFlash(
            'info',
            Yii::t('users', 'Your account has been created and a message with further instructions has been sent to your email')
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'username' => Yii::t('users', 'Username'),
            'password' => Yii::t('users', 'Password'),
        ];
    }
}