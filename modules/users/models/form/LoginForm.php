<?php

namespace app\modules\users\models\form;

use Yii;
use yii\base\Model;
use app\modules\users\models\User;
use app\modules\users\helpers\Password;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    protected $user;
    protected $module;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['login', 'validateConfirmation'],
        ];
    }

    /**
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->user || !Password::validate($this->$attribute, $this->user->password_hash)) {
                $this->addError($attribute, Yii::t('users', 'Invalid login or password'));
            }
        }
    }

    /**
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateConfirmation($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->user) {

                $confirmationRequired = $this->module->enableConfirmation && !$this->module->enableUnconfirmedLogin;
                if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                    $this->addError($attribute, Yii::t('users', 'You need to confirm your email address'));
                }

                if ($this->user->getIsBlocked()) {
                    $this->addError($attribute, Yii::t('users', 'Your account has been blocked'));
                }
            }
        }
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->user, $this->rememberMe ? $this->module->rememberMe : 0);
        } else {
            return false;
        }
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        $this->module = Yii::$app->getModule('users');

        if (filter_var($this->login, FILTER_VALIDATE_EMAIL)) {
            $this->user = User::find()->byEmail($this->login)->one();
        } else {
            $this->user = User::find()->byUsername($this->login)->one();
        }

        return true;
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'login'      => Yii::t('users', 'Login or email'),
            'password'   => Yii::t('users', 'Password'),
            'rememberMe' => Yii::t('users', 'Remember me'),
        ];
    }
}
