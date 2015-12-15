<?php

namespace app\modules\users\models\form;

use Yii;
use yii\base\Model;
use app\modules\users\models\User;
use app\modules\users\models\Token;

/**
 * LoginForm is the model behind the login form.
 */
class RecoveryForm extends Model
{
    const SCENARIO_REQUEST = 'request';
    const SCENARIO_RESET = 'reset';

    const BEFORE_RECOVERY = 'beforeRecovery';
    const AFTER_RECOVERY = 'afterRecovery';

    public $email;
    public $password;
    public $passwordRepeat;
    public $reCaptcha;

    protected $user;

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'recovery-form';
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            self::SCENARIO_REQUEST => ['email', 'reCaptcha'],
            self::SCENARIO_RESET => ['password', 'passwordRepeat'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            // email rules
            'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailPattern' => ['email', 'email'],
            'emailExist' => [
                'email',
                'exist',
                'targetClass' => User::className(),
                'message' => Yii::t('users', 'There is no user with this email address'),
            ],
            'emailUnconfirmed' => ['email', 'validateConfirmation'],
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

    /**
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateConfirmation($attribute)
    {
        if (!$this->hasErrors()) {

            $module = Yii::$app->getModule('users');
            $this->user = User::find()->byEmail($this->$attribute)->one();

            if($this->user && $module->enableConfirmation && !$this->user->getIsConfirmed()) {
                $this->addError($attribute, Yii::t('users', 'You need to confirm your email address'));
            }
        }
    }

    public function sendRecoveryMessage()
    {
        if ($this->validate()) {

            $token = new Token(['user_id' => $this->user->id, 'type' => Token::TYPE_RECOVERY]);
            $token->save(false);

            $this->user->sendMail(
                Yii::t('users', 'Password recovery on {0}', Yii::$app->name),
                'recovery',
                ['token' => $token]
            );

            return true;
        }

        return false;
    }

    public function resetPassword(User $user)
    {
        if ($this->validate()) {

            $this->trigger(self::BEFORE_RECOVERY);
            if ($user->changePassword($this->password)) {
                $this->trigger(self::AFTER_RECOVERY);
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('users', 'New password'),
            'passwordRepeat' => Yii::t('users', 'Repeat password'),
            'reCaptcha' => Yii::t('users', 'Captcha'),
        ];
    }
}
