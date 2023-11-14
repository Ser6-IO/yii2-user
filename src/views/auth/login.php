<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

use ser6io\yii2bs5widgets\ActiveForm;
use yii\bootstrap5\Html;

use ser6io\yii2user\models\LoginForm;

$this->title = Yii::$app->name . ' - Login';

?>

<div class="modal" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4" id="loginModalLabel"><?= APP_NAME ?></h1>
            </div>
            <div class="modal-body">

                <p>Please login:</p>

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'action' => $model->scenario == LoginForm::LINK_LOGIN ? '/user/auth/link-login' : '/user/auth/login',
                    'options' => ['name' => 'login-form']
                ]); ?>

                <?php if ($model->scenario == LoginForm::LINK_LOGIN): ?>

                    <?= $form->field($model, 'email', ['inputOptions' => ['autocomplete' => 'email']])->textInput(['autofocus' => true]) ?>

                    <p class="mt-2 text-muted card-text"><small>We'll email you a link to log in, or you can <a href="/user/auth/login">log in with a password</a> instead.</small></p>

                    <?php $buttonCaption = 'Continue'; ?>

                <?php elseif ($model->scenario == LoginForm::PASSWORD_LOGIN): ?>

                    <?= $form->field($model, 'username', ['inputOptions' => ['autocomplete' => 'email']])->textInput(['autofocus' => true])->label('Username or Email') ?>

                    <?= $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'current-password'], 'errorOptions' => ['encode' => false]])->passwordInput() ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <p class="mt-2 text-muted card-text"><small>Forgot your password? <a href="/user/auth/link-login">Log in with a link</a> instead.</small></p>

                    <?php $buttonCaption = 'Login'; ?>

                <?php endif; ?>

                <?php ActiveForm::end(); ?>
            
            </div>
            
            <div class="modal-footer">
                <?= Html::submitButton($buttonCaption, [
                        'class' => 'btn btn-primary w-100', 
                        'name' => 'login-button',
                        'form' => 'login-form',
                ]) ?>
                <p class="text-muted"><small>By logging in, you agree to our Terms and Conditions and Privacy Policy.</small></p>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    window.onload = () => {
        const myModalElement = document.getElementById('loginModal')
        const myInput = document.getElementById('loginform-username')
        const myModal = new bootstrap.Modal(myModalElement);

        myModalElement.addEventListener('shown.bs.modal', () => {
            myInput.focus()
        })

        myModal.show();
    }
</script>




   

