<?php
include '../../components/header.php';
include '../../components/navbar.php';
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
                    <p class="card-text">BWP<?= number_format($totalAmount, 2) ?></p>
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
</div>

<!-- SALES CHART SECTION -->
<div class="container mt-5">
    <div id="sales_chart_div" class="chart-container"></div>
</div>
<br><br>

<style>
    .chart-container {
        width: 90%;
        height: 500px;
        margin: 0 auto;
    }
</style>

<!-- Google Charts Script -->
<script type="text/javascript">
    google.charts.load('current', {
        packages: ['corechart']
    });
    google.charts.setOnLoadCallback(drawSalesChart);

    function drawSalesChart() {
        const rawData = <?php echo json_encode($chartData); ?>;

        // Data for sales chart
        const salesData = new google.visualization.DataTable();
        salesData.addColumn('string', 'Month');
        salesData.addColumn('number', 'Total Sales (BWP)');
        rawData.forEach(item => {
            salesData.addRow([item.month, item.total_amount]);
        });

        const salesOptions = {
            title: 'Monthly Sales',
            titleTextStyle: {
                fontSize: 25, // Set the desired font size for the title
                bold: true, // Optionally make the title bold
                color: '#333' // Optionally set a custom color for the title
            },
            hAxis: {
                title: 'Month',
                textStyle: {
                    fontSize: 12
                },
                titleTextStyle: {
                    fontSize: 14
                }
            },
            vAxis: {
                title: 'Sales (BWP)',
                textStyle: {
                    fontSize: 12
                },
                titleTextStyle: {
                    fontSize: 14
                },

            },
            legend: {
                position: 'bottom'
            },
            colors: ['#FF5722'], // Custom line color (Orange)
            curveType: 'function', // Smooth curve for the line chart
            pointSize: 6, // Size of the points on the line
            backgroundColor: '#f9f9f9', // Light background for the chart
            tooltip: {
                textStyle: {
                    color: '#000', // Black text in tooltips
                    fontSize: 12
                },
                showColorCode: true
            }
        };

        const salesChart = new google.visualization.LineChart(document.getElementById('sales_chart_div'));
        salesChart.draw(salesData, salesOptions);
    }
</script>

<?php include '../../components/footer.php'; ?>