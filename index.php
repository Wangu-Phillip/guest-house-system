<?php include 'components/header.php'; ?>
<?php include 'components/toast.php'; ?>

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
