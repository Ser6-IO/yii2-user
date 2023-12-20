<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\bootstrap5\Html;
use ser6io\yii2bs5widgets\ActiveForm;

$this->title = 'Change password';
?>

<div class="modal-header">
    <h1 class="modal-title fs-4" id="loginModalLabel"><?= APP_NAME ?></h1>
    <?php if (isset($closeBtn)) echo Html::a('', $closeBtn, ['class' => 'btn-close']) ?>
</div>

<?php $form = ActiveForm::begin(['id' => 'change-password-form']); ?>

<div class="modal-body">

    <h5 class="modal-title"><?= $this->title ?></h5>
    
    <?= $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput(['autofocus' => true]) ?>
    <p class="mt-2 text-muted card-text"><small>Forgot your current password? <a href="/user/auth/request-password-reset">Reset it</a>.</small></p>
            
    <?= $form->field($model, 'new_password', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput() ?>
    
    <?= $form->field($model, 'new_password_repeat', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput() ?>

</div>

<div class="modal-footer me-auto">

    <?= Html::submitButton('Change password', ['class' => 'btn btn-primary']) ?>

</div>

<?php ActiveForm::end(); ?>
    
<script>
    window.onload = () => { initLoginModal() }
</script>
