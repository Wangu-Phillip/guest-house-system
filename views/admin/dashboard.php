<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../components/toast.php';

// Fetch data from database
include '../../backend/db_connection.php';

// Sample queries for data display
$totalGuests = $conn->query("SELECT COUNT(*) AS count FROM guests")->fetch_assoc()['count'];
$totalAmount = $conn->query("SELECT COUNT(*) AS count FROM bookings")->fetch_assoc()['count'];
$totalBookings = $conn->query("SELECT COUNT(*) AS count FROM bookings")->fetch_assoc()['count'];
?>
<div class="container mt-5">
    <div class="row">
        <h3>Monthly Statistics</h3>
        <!-- Dashboard Overview Cards -->
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Guests</h5>
                    <p class="card-text"><?= $totalGuests ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body shadow">
                    <h5 class="card-title">Total Earnings</h5>
                    <p class="card-text"><?= $totalAmount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text"><?= $totalBookings ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Details Table -->
    <div class="mt-5">
        <h3>Recent Bookings</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Guest Name</th>
                    <th>Room</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $bookings = $conn->query("SELECT bookings.date, guests.firstname, guests.lastname, rooms.room_type, bookings.price FROM bookings JOIN guests ON bookings.guest_id = guests.guest_id JOIN rooms ON bookings.room_id = rooms.room_id LIMIT 5");
                while ($row = $bookings->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['date']}</td>
                        <td>{$row['firstname']} {$row['lastname']}</td>
                        <td>{$row['room_type']}</td>
                        <td>{$row['price']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../components/footer.php'; ?>
