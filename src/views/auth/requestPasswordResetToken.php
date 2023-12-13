<?php

use yii\bootstrap5\Html;
use ser6io\yii2bs5widgets\ActiveForm;

$this->title = 'Request password reset';

?>

<div class="modal-header">

    <h1 class="modal-title fs-4" id="loginModalLabel"><?= $this->title ?></h1>

</div>

<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

<div class="modal-body">
    
    <p>Please enter your email address and we'll send you a link to create a new password.</p>
    
    <?= $form->field($model, 'email', ['inputOptions' => ['autocomplete' => 'email']])->textInput(['autofocus' => true]) ?>
    
</div>

<div class="modal-footer me-auto">

    <p class="text-muted"><?= Html::submitButton('Request new password', ['class' => 'btn btn-primary']) ?></p>

</div>

<?php ActiveForm::end(); ?>
    
<script>
    window.onload = () => { initLoginModal() }
</script>


