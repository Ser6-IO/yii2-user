<?php

$this->title = Yii::$app->name . ' - Login';

?>

<div class="modal-header">
    <h1 class="modal-title fs-4" id="loginModalLabel"><?= $title ?></h1>
</div>
<div class="modal-body">
    <p class="card-text"><?= $body ?></p>
</div>
<div class="modal-footer me-auto">
    <p class="text-muted"><?= $footer ?></p>
</div>
    
<script>
    window.onload = () => { initLoginModal() }
</script>