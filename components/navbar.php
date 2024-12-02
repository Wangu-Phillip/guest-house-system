<?php

session_start();
$role = $_SESSION['role'];

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- NAV BAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">WBMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <!-- Conditional Rendering for Dashboard -->
                <?php if ($role !== 'employee') : ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'bookings.php') ? 'active' : '' ?>" href="bookings.php">Bookings</a>
                </li>

                <!-- Conditional Rendering for Users -->
                <?php if ($role !== 'employee') : ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'users.php') ? 'active' : '' ?>" href="users.php">Users</a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'rooms.php') ? 'active' : '' ?>" href="rooms.php">Rooms</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'calendar.php') ? 'active' : '' ?>" href="calendar.php">Calendar</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'reports.php') ? 'active' : '' ?>" href="reports.php">Reports</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../../views/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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