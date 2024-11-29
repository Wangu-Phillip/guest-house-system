
<?php include '../components/header.php'; ?>
<?php include '../../components/toast.php'; ?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100">
        <div class="col-md-6 bg-white p-5">
            <h2 class="text-center mb-4">Welcome Back...</h2>
            <form method="POST" action="../backend/process_login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Username/Email</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <a href="forgot_password.php" class="text-decoration-none">Forgot password?</a>
            </div>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>
