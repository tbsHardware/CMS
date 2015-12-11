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

    private $_user;

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

        $this->_user = new User(['scenario' => User::SCENARIO_REGISTER]);
        $this->_user->setAttributes($this->attributes);

        return $this->_user->register();
    }

    public function getUser()
    {
        return $this->_user;
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