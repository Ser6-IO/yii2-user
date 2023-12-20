<?php
namespace ser6io\yii2user\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * Password reset form
 */
class PasswordResetForm extends Model
{
    public $password;
    public $password_repeat;

    /**
     * @var app\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {

            $link = Yii::$app->urlManager->createAbsoluteUrl(['/user/auth/request-password-reset']);

            $message = "This password reset link has expired. Make sure you use the link from the latest email you received with the password reset instructions, or request a new one here: $link.";

            throw new InvalidArgumentException($message);
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required'],
            [['password', 'password_repeat'], 'string', 'min' => 6],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message'=>'The new password does not match.'],
      

            //['password', \saavtek\LoginWD\Unlock::className(), 'username' => $this->_user->username],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password'=>'New password',
            'password_repeat'=>'Confirm new password',
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        if ($user->status == User::STATUS_INACTIVE) {
            $user->status = User::STATUS_ACTIVE;
        }
        Yii::info("Password reset: " . $user->username, __METHOD__);
        return $user->save(false);
    }
}
