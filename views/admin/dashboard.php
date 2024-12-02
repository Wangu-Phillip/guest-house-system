<?php
include '../../components/header.php';
include '../../components/navbar.php';

// Check if the user is logged in and has a role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login or access denied page
    header("Location: ../../index.php");
    exit(); // Stop further execution
}

include '../../components/toast.php';

// Fetch data from the database
include '../../backend/db_connection.php';



// Queries for total statistics
$currentMonth = date('m'); // Get current month as a two-digit number (e.g., 11 for November)
$currentYear = date('Y'); // Get current year

$totalGuests = $conn->query("
    SELECT SUM(number_of_guests) AS count 
    FROM bookings b, guests g
    WHERE b.guest_id = g.guest_id
    AND MONTH(b.check_out) = $currentMonth
    AND YEAR(b.check_out) = $currentYear
")->fetch_assoc()['count'];

$totalBookings = $conn->query("
    SELECT COUNT(*) AS count 
    FROM bookings 
    WHERE check_out IS NOT NULL 
    AND check_out != ''
    AND MONTH(check_out) = $currentMonth
    AND YEAR(check_out) = $currentYear
")->fetch_assoc()['count'];

$totalAmount = $conn->query("
    SELECT SUM(r.price) AS total 
    FROM bookings b, rooms r
    WHERE b.check_out IS NOT NULL 
    AND b.check_out != '' 
    AND r.room_id = b.room_id
    AND MONTH(b.check_out) = $currentMonth
    AND YEAR(b.check_out) = $currentYear
")->fetch_assoc()['total'] ?? 0;

// Query to get monthly statistics
$monthlyStats = $conn->query("
    SELECT 
        MONTHNAME(b.check_out) AS month_name, 
        YEAR(b.check_out) AS year,
        SUM(r.price) AS total_amount, 
        COUNT(*) AS total_bookings 
    FROM bookings b, rooms r
    WHERE check_out IS NOT NULL AND check_out != ''
    AND r.room_id = b.room_id
    GROUP BY YEAR(check_out), MONTH(check_out)
    ORDER BY YEAR(check_out), MONTH(check_out)
");

// Prepare data for Google Charts
$chartData = [];
while ($row = $monthlyStats->fetch_assoc()) {
    $chartData[] = [
        'month' => $row['month_name'] . ' ' . $row['year'], // Combine Month and Year
        'total_amount' => (float)$row['total_amount'],
        'total_bookings' => (int)$row['total_bookings']
    ];
}

// RECENT BOOKINGS FOR DISPLAY
$recentBookings = $conn->query("
    SELECT bookings.date, 
           CONCAT(guests.firstname, ' ', guests.lastname) AS guest_name, 
           rooms.room_type, 
           bookings.price 
    FROM bookings 
    JOIN guests ON bookings.guest_id = guests.guest_id 
    JOIN rooms ON bookings.room_id = rooms.room_id 
    WHERE bookings.check_out IS NOT NULL AND bookings.check_out != ''
    ORDER BY bookings.date DESC
    LIMIT 5
");
?>

<!-- MONTHLY STATISTICS -->
<div class="container mt-5">

    <!-- DASHBOARD OVERVIEW CARDS -->
    <div class="row g-3">
        <h3 class="mb-4 text-center">Monthly Statistics</h3>
        <div class="col-12 col-md-4">
            <div class="card text-center shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Guests</h5>
                    <p class="card-text fs-4"><?= $totalGuests == null ? 0 : $totalGuests ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card text-center shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Earnings</h5>
                    <p class="card-text fs-4">BWP<?= number_format($totalAmount, 2) ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card text-center shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text fs-4"><?= $totalBookings ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SALES CHART SECTION -->
<div class="container mt-5">
    <h3 class="text-center">Monthly Revenue Chart</h3>
    <div id="sales_chart_div" class="chart-container mx-auto"></div>
</div>

<!-- RECENT BOOKINGS -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Recent Bookings</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $recentBookings->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['guest_name']) ?></td>
                        <td><?= htmlspecialchars($row['room_type']) ?></td>
                        <td>BWP<?= number_format($row['price'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<br><br>

<style>
    .chart-container {
        width: 100%;
        max-width: 1000px; /* Adjust max-width for larger screens */
        height: auto; /* Let height adjust dynamically */
        aspect-ratio: 16 / 9; /* Maintain a consistent aspect ratio */
        margin: 0 auto;
    }
</style>

<!-- Google Charts Script -->
<script type="text/javascript">
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawSalesChart);

    let salesChart;

    function drawSalesChart() {
        const rawData = <?php echo json_encode($chartData); ?>;

        const salesData = new google.visualization.DataTable();
        salesData.addColumn('string', 'Month');
        salesData.addColumn('number', 'Total Revenue (BWP)');
        rawData.forEach(item => {
            salesData.addRow([item.month, item.total_amount]);
        });

        const salesOptions = {
            title: 'Monthly Revenue',
            titleTextStyle: {
                fontSize: 18,
                bold: true,
                color: '#333',
            },
            hAxis: {
                title: 'Month',
                textStyle: { fontSize: 10 },
                titleTextStyle: { fontSize: 12 },
            },
            vAxis: {
                title: 'Sales (BWP)',
                textStyle: { fontSize: 10 },
                titleTextStyle: { fontSize: 12 },
            },
            legend: { position: 'bottom' },
            colors: ['#FF5722'],
            curveType: 'function',
            pointSize: 4,
            backgroundColor: '#f9f9f9',
            tooltip: {
                textStyle: { color: '#000', fontSize: 10 },
                showColorCode: true,
            },
        };

        const container = document.getElementById('sales_chart_div');
        salesChart = new google.visualization.LineChart(container);
        salesChart.draw(salesData, salesOptions);
    }

    // Redraw the chart on window resize for responsiveness
    window.addEventListener('resize', () => {
        if (salesChart) {
            drawSalesChart();
        }
    });
</script>


<?php include '../../components/footer.php'; ?>
