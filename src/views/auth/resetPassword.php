<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\bootstrap5\Html;
use ser6io\yii2bs5widgets\ActiveForm;

$this->title = 'Reset password';
?>

<div class="container">
    <div class="row justify-content-md-center pt-2">
        <div class="col-md-6 p-3 border rounded p-2 mb-2 border-opacity-50">
            <h2><?= Html::encode($this->title) ?></h2>
            <p class="">Please choose your new password:</p>
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'new-password']])->passwordInput(['autofocus' => true]) ?>
                <div class='d-grid my-3'>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
