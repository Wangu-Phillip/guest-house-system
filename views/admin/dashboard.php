<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../components/toast.php';

// Fetch data from the database
include '../../backend/db_connection.php';

// Queries for total statistics
$totalGuests = $conn->query("SELECT COUNT(*) AS count FROM guests")->fetch_assoc()['count'];
$totalBookings = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE check_out IS NOT NULL AND check_out != ''")->fetch_assoc()['count'];
$totalAmount = $conn->query("
    SELECT SUM(price) AS total 
    FROM bookings 
    WHERE check_out IS NOT NULL AND check_out != ''
")->fetch_assoc()['total'] ?? 0;

// Query to get monthly statistics
$monthlyStats = $conn->query("
    SELECT 
        MONTHNAME(check_out) AS month_name, 
        YEAR(check_out) AS year,
        SUM(price) AS total_amount, 
        COUNT(*) AS total_bookings 
    FROM bookings 
    WHERE check_out IS NOT NULL AND check_out != ''
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

// Recent bookings for display
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
                    <p class="card-text"><?= number_format($totalAmount, 2) ?> BWP</p>
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

    <!-- LINE CHART GRAPH -->
    <div class="container my-5">
        <div class="d-flex justify-content-center">
            <div id="chart_div" style="width: 90%; height: 500px;"></div>
        </div>
    </div>

</div>

<!-- Google Charts -->
<script type="text/javascript">
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Chart data in JSON format
        const rawData = <?php echo json_encode($chartData); ?>;

        // Create a DataTable
        const data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', 'Total Amount (BWP)');
        data.addColumn('number', 'Total Bookings');

        // Populate DataTable
        rawData.forEach(item => {
            data.addRow([item.month, item.total_amount, item.total_bookings]);
        });

        // Chart options
        const options = {
            title: 'Monthly Statistics',
            hAxis: { title: 'Month', textStyle: { fontSize: 12 } },
            vAxis: { title: 'Amount and Bookings', textStyle: { fontSize: 12 } },
            seriesType: 'bars',
            series: { 1: { type: 'line' } }, // Total Bookings as a line chart
            legend: { position: 'bottom' },
        };

        // Create and render the chart
        const chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>

<?php include '../../components/footer.php'; ?>
