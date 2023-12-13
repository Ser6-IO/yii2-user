<?php

namespace ser6io\yii2user\models;

use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'filter', 'filter'=>'strtolower'],
            ['email', 'string', 'max' => 255],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => [User::STATUS_ACTIVE, User::STATUS_INACTIVE]],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => [User::STATUS_ACTIVE, User::STATUS_INACTIVE],
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                '@vendor/ser6-io/yii2-user/src/mail/passwordResetToken-html',
                ['user' => $user]
            )
            ->setFrom([APP_SUPPORT_EMAIL => APP_NAME])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . APP_NAME)
            ->send();
    }
}
