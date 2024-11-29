<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">WBMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'bookings.php') ? 'active' : '' ?>" href="bookings.php">Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'users.php') ? 'active' : '' ?>" href="users.php">Users</a>
                </li>
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
