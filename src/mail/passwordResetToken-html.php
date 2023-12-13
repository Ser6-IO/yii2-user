<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/auth/reset-password', 'token' => $user->password_reset_token]);

$loginLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/auth/request-password-reset']);

?>

<h1>Reset Password for <?= APP_NAME ?></h1>

<p>We received a request to create a new password for <?= APP_NAME ?> using your email address: <?= $user->username ?>. Please click the link below to continue:</p>

<p><?= Html::a('Create new password', $resetLink) ?></p>

<p>Please note that this link will expire in one (1) hour.<br>
To generate a new link, please click <?= Html::a('here', $loginLink) ?>.</p>

<p>If you did not request this, you can ignore this email.</p>

<hr>

<p>Do not reply to this email. It is sent from an unmonitored address. If you need assistance, please contact us at <?= APP_SUPPORT_EMAIL ?>.</p>