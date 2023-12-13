<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\bootstrap5\Html;
use ser6io\yii2bs5widgets\ActiveForm;

$this->title = 'Reset password';
?>

<div class="modal-header">

    <h1 class="modal-title fs-4" id="loginModalLabel"><?= $this->title ?></h1>

</div>

<?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

<div class="modal-body">
    
    <p class="">Please choose your new password:</p>
            
    <?= $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput(['autofocus' => true]) ?>
    
</div>

<div class="modal-footer me-auto">

    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>

</div>

<?php ActiveForm::end(); ?>
    
<script>
    window.onload = () => { initLoginModal() }
</script>
