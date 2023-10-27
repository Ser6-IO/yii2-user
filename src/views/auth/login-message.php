<?php

$this->title = Yii::$app->name . ' - Login';

?>

<div class="modal" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4" id="loginModalLabel"><?= $title ?></h1>
            </div>
            <div class="modal-body">
                <p class="card-text"><?= $body ?></p>
            </div>
            <div class="modal-footer me-auto">
                <p class="text-muted"><?= $footer ?></p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.onload = () => {
        const myModalElement = document.getElementById('loginModal')
        const myModal = new bootstrap.Modal(myModalElement);
        myModal.show();
    }
</script>
