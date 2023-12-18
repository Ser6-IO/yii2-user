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
    public $email;
    public $password;
    public $token;
    public $rememberMe = true;

    private $_user = false;

    const PASSWORD_LOGIN = 'passwordLogin';
    const LINK_LOGIN = 'linkLogin';
    const LINK_LOGIN_CALLBACK = 'linkLoginCallback';

    /**
     * Scenarios
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::PASSWORD_LOGIN] = ['username', 'password', 'rememberMe'];
        $scenarios[self::LINK_LOGIN] = ['email'];
        $scenarios[self::LINK_LOGIN_CALLBACK] = ['token'];
        return $scenarios;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            
            [['username', 'email'], 'trim'],
            ['username', 'string', 'max' => 255],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'email'],

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
        //Move to module/component
        if (Yii::$app->controller->module->id != APP_ID) {
            $module = APP_ID . '\\' . Yii::$app->controller->module->id;
        } else {
            $module = Yii::$app->controller->module->id;
        }
        $logCategory = "$module\\" . Yii::$app->controller->id . '\\' . Yii::$app->controller->action->id;



        if ($user = User::findByUsername($this->email)) {
        
            $user->generateEmailVerificationToken();
            
            if ($user->save()) {
                return Yii::$app->mailer->compose('@ser6io/yii2user/mail/login-link-html', ['user' => $user, 'token' => $user->verification_token])
                    ->setTo($this->email)
                    ->setFrom([APP_SENDER_EMAIL => APP_SENDER_NAME])
                    //->setReplyTo([$this->email => $this->name])
                    ->setSubject('Your login for ' . APP_NAME)
                    //->setTextBody($this->body)
                    ->send();
            } else {

                Yii::error("Failed to save user verification Token for $this->email " . json_encode($user->errors), $logCategory);
                return false;
            }
            
        } else {
            Yii::error("Failed login attempt by $this->email", $logCategory);
            return false;
        }
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

            $login = Yii::$app->user->login($this->getUserByToken(), 3600*24*30);

            if ($login) {
                $this->_user->removeEmailVerificationToken();
                $this->_user->save();
                return true;
            }            
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

}
