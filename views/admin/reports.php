<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../backend/db_connection.php';

// Set the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// 1. Statistics for the current month and year
$statistics = $conn->query("
    SELECT 
        COUNT(DISTINCT g.guest_id) AS total_guests,
        COUNT(DISTINCT b.booking_id) AS total_bookings,
        COUNT(DISTINCT r.room_id) AS total_rooms
    FROM bookings b
    JOIN guests g ON b.guest_id = g.guest_id
    JOIN rooms r ON b.room_id = r.room_id
    WHERE MONTH(b.date) = $currentMonth AND YEAR(b.date) = $currentYear
")->fetch_assoc();

// 2. Total salaries for the month
// Assuming you have a `salaries` table, modify this query to match your actual schema
$totalSalaries = $conn->query("
    SELECT SUM(salary) AS total_salaries
    FROM users
")->fetch_assoc()['total_salaries'] ?? 0;

// 3. Turnover or total revenue on accommodation for the month
$totalRevenue = $conn->query("
    SELECT SUM(r.price) AS total_revenue
    FROM bookings b, rooms r
    WHERE MONTH(b.check_out) = $currentMonth AND YEAR(b.check_out) = $currentYear
    AND b.room_id = r.room_id
")->fetch_assoc()['total_revenue'] ?? 0;

// 4. Number of guests by country
$guestsByCountry = $conn->query("
    SELECT country, COUNT(*) AS total_guests
    FROM guests
    GROUP BY country
    ORDER BY total_guests DESC
")->fetch_all(MYSQLI_ASSOC);

// 5. Percentage of each country's guests from the total number of guests
$totalGuests = $conn->query("
    SELECT COUNT(*) AS total_guests
    FROM guests
")->fetch_assoc()['total_guests'];

$guestsPercentageByCountry = [];
foreach ($guestsByCountry as $row) {
    $guestsPercentageByCountry[] = [
        'country' => $row['country'],
        'percentage' => round(($row['total_guests'] / $totalGuests) * 100, 2),
    ];
}
?>

<div class="container mt-5">
    <h2>System Reports</h2>
    <hr>

    <!-- 1. Statistics for the current month and year -->
    <h4>Statistics for <?= date('F Y') ?></h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Total Guests</th>
                <th>Total Bookings</th>
                <th>Total Rooms (Booked)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $statistics['total_guests'] ?? 0 ?></td>
                <td><?= $statistics['total_bookings'] ?? 0 ?></td>
                <td><?= $statistics['total_rooms'] ?? 0 ?></td>
            </tr>
        </tbody>
    </table>

    <!-- 2. Total Salaries for the Month -->
    <h4>Total Salaries for <?= date('F Y') ?></h4>
    <p>Total Salaries Paid: <?= number_format($totalSalaries, 2) ?> BWP</p>

    <!-- 3. Turnover or Total Revenue for the Month -->
    <h4>Turnover for <?= date('F Y') ?></h4>
    <p>Total Revenue from Accommodation: <?= number_format($totalRevenue, 2) ?> BWP</p>

    <!-- 4. Number of Guests by Country -->
    <h4>Number of Guests by Country</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Country</th>
                <th>Number of Guests</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($guestsByCountry as $row): ?>
                <tr>
                    <td><?= $row['country'] ?></td>
                    <td><?= $row['total_guests'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 5. Percentage of Each Country's Guests -->
    <h4>Percentage of Guests by Country</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Country</th>
                <th>Percentage (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($guestsPercentageByCountry as $row): ?>
                <tr>
                    <td><?= $row['country'] ?></td>
                    <td><?= $row['percentage'] ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<br><br>

<?php include '../../components/footer.php'; ?>
