<?php include '../components/header.php'; ?>

<!-- SUCCESS/ERROR TOAST -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div id="toastNotification" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-light">
            <span id="toastIcon" class="me-2"></span>
            <strong id="toastHeading" class="me-auto">Message</strong>
            <small id="toastTime">Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            <!-- Toast Message -->
        </div>
        <div id="toastProgress" class="progress position-relative bottom-0 start-2 w-100" style="height: 3px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

<!-- USER REGISTRATION FORM -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">User Registration</h2>
            <form method="POST" action="../backend/process_register.php">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>
        </div>
    </div>
</div>
<?php include '../components/footer.php'; ?>
