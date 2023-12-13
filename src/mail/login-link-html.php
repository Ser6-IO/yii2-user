<h1>Log in to <?= APP_NAME ?></h1>

<p>We received a request to log in to <?= APP_NAME ?> using your email address: <?= $user->username ?>. Please click the link below to continue:</p>

<p>
    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/user/auth/login-callback', 'token' => $token]) ?>">Login</a>
</p>

<br>

<p>If you did not request this, you can ignore this email.</p>
<hr>
<p>Do not reply to this email. It is sent from an unmonitored address. If you need assistance, please contact us at <?= APP_SUPPORT_EMAIL ?>.</p>