<?php include 'components/header.php'; ?>

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

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100">
        <!-- Login Form -->
        <div class="col-12 col-md-8 col-lg-5 bg-white p-4 mx-auto shadow rounded-3">
            <h2 class="text-center mb-4">Login</h2>
            <form method="POST" action="backend/process_login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
                <div class="text-center mt-3">
                    <a href="views/register.php" class="text-decoration-none">Don't have an account? Register</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>
