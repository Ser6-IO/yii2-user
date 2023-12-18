<?php $this->beginContent('@app/views/layouts/login.php'); ?>
    
    <div class="modal" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
                <?= $content ?>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        function initLoginModal(focusInputId = null) {
            const myModalElement = document.getElementById('loginModal')        
            const myModal = new bootstrap.Modal(myModalElement);
            if (focusInputId != null) {
                const myInput = document.getElementById(focusInputId);
                myModalElement.addEventListener('shown.bs.modal', () => {
                    myInput.focus()
                })
            }
            myModal.show();
        }
    </script>

<?php $this->endContent() ?>