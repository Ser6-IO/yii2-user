<?php
namespace ser6io\yii2user\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * Password reset form
 */
class PasswordChangeForm extends Model
{
    public $password;
    public $new_password;
    public $new_password_repeat;

    /**
     * @var app\models\User
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'new_password', 'new_password_repeat'], 'required'],
            [['password', 'new_password', 'new_password_repeat'], 'string', 'min' => 6],
            
            ['password', 'validatePassword'],

            [['new_password'], 'compare', 'compareAttribute' => 'password', 'operator' => '!=', 'message'=>'The new password cannot be the same as the old password.'],
            
            [['new_password_repeat'], 'compare', 'compareAttribute' => 'new_password', 'message'=>'The new password does not match.'],
      

            //['password', \saavtek\LoginWD\Unlock::className(), 'username' => $this->_user->username],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password'=>'Current password',
            'new_password'=>'New password',
            'new_password_repeat'=>'Confirm new password',
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
            //$user = $this->getUser();
            $this->_user = Yii::$app->user->identity;

            if (!$this->_user || !$this->_user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->new_password);
        $user->removePasswordResetToken();
        if ($user->status == User::STATUS_INACTIVE) {
            $user->status = User::STATUS_ACTIVE;
        }
        \ser6io\yii2user\components\UserActionLog::trace("Password changed for " . $user->username);
        return $user->save(false);
    }
}
