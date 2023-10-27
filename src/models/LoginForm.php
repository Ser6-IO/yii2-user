<?php

namespace ser6io\yii2user\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $token;
    public $rememberMe = true;

    private $_user = false;

    const LINK_LOGIN = 'linkLogin';
    const LINK_LOGIN_CALLBACK = 'linkLoginCallback';

    /**
     * Scenarios
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::LINK_LOGIN] = ['username'];
        $scenarios[self::LINK_LOGIN_CALLBACK] = ['token'];
        return $scenarios;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'email'],
            ['username', 'trim'],
            ['username', 'filter', 'filter' => 'strtolower'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['token', 'validateToken'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Validates the token.
     * This method serves as the inline validation for token.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validateToken($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUserByToken();

            if (!$user || !$user->isPasswordResetTokenValid($this->token)) {
                $this->addError($attribute, 'Invalid token.');
            }
        }
    }

    /**
     * Sends a login link to the user, if valid.
     */
    public function sendLoginLink()
    {
        if ($user = User::findByUsername($this->username)) {
        
            $user->generateEmailVerificationToken();
            
            if ($user->save()) {
                return Yii::$app->mailer->compose('@ser6io/yii2user/mail/login-link-html', ['user' => $user, 'token' => $user->verification_token])
                    ->setTo($this->username)
                    ->setFrom([APP_SENDER_EMAIL => APP_SENDER_NAME])
                    //->setReplyTo([$this->email => $this->name])
                    ->setSubject('Your login for ' . APP_NAME)
                    //->setTextBody($this->body)
                    ->send();
            }
            return false;
        }
        return false;
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Logs in a user using the provided token.
     * @return bool whether the user is logged in successfully
     */
    public function loginByToken()
    {
        if ($this->validate()) {
            $this->username = $this->_user->username;
            return Yii::$app->user->login($this->getUserByToken(), 3600*24*30);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

    /**
     * Finds user by [[token]]
     */
    public function getUserByToken()
    {
        if ($this->_user === false) {
            $this->_user = User::findByVerificationToken($this->token);
        }
        return $this->_user;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Email',
        ];
    }
}
