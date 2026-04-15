<?php
// toast_notifications.php - Save this in your Include folder
if(isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 11">
    <?php if(isset($_SESSION['success'])): ?>
    <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
    <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success toast
    var successToast = document.getElementById('successToast');
    if(successToast) {
        var toast = new bootstrap.Toast(successToast, {
            autohide: true,
            delay: 3000
        });
        toast.show();
    }
    
    // Show error toast
    var errorToast = document.getElementById('errorToast');
    if(errorToast) {
        var toast = new bootstrap.Toast(errorToast, {
            autohide: true,
            delay: 5000
        });
        toast.show();
    }
});
</script>
<?php endif; ?>