<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page
    header("Location: ../../views/login.php");
    exit(); // Stop further execution of the script
}

include '../../backend/db_connection.php';

// Get selected month and year from POST request
$selectedMonth = $_POST['month'] ?? date('m'); // Default to current month
$selectedYear = $_POST['year'] ?? date('Y'); // Default to current year

// 1. Statistics
$statistics = $conn->query("
    SELECT 
        COUNT(DISTINCT g.guest_id) AS total_guests,
        COUNT(DISTINCT b.booking_id) AS total_bookings,
        COUNT(DISTINCT r.room_id) AS total_rooms
    FROM bookings b
    JOIN guests g ON b.guest_id = g.guest_id
    JOIN rooms r ON b.room_id = r.room_id
    WHERE MONTH(b.date) = $selectedMonth AND YEAR(b.date) = $selectedYear
")->fetch_assoc();

// 2. Total Salaries
$totalSalaries = $conn->query("
    SELECT SUM(salary) AS total_salaries
    FROM users
")->fetch_assoc()['total_salaries'] ?? 0;

// 3. Total Revenue
$totalRevenue = $conn->query("
    SELECT SUM(r.price) AS total_revenue
    FROM bookings b, rooms r
    WHERE MONTH(b.check_out) = $selectedMonth AND YEAR(b.check_out) = $selectedYear
    AND b.room_id = r.room_id
")->fetch_assoc()['total_revenue'] ?? 0;

// 4. Guests By Country
$guestsByCountry = $conn->query("
    SELECT country, COUNT(*) AS total_guests
    FROM guests
    WHERE MONTH(created_At) = $selectedMonth AND YEAR(created_At) = $selectedYear
    GROUP BY country
    ORDER BY total_guests DESC
")->fetch_all(MYSQLI_ASSOC);

// 5. Guests Percentage By Country
$totalGuests = $conn->query("
    SELECT COUNT(*) AS total_guests
    FROM guests
    WHERE MONTH(created_At) = $selectedMonth AND YEAR(created_At) = $selectedYear
")->fetch_assoc()['total_guests'];

$guestsPercentageByCountry = [];
foreach ($guestsByCountry as $row) {
    $guestsPercentageByCountry[] = [
        'country' => $row['country'],
        'percentage' => round(($row['total_guests'] / $totalGuests) * 100, 2),
    ];
}

// Prevent extra output
if (ob_get_length()) ob_clean();

// Set headers for CSV export
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=report_$selectedMonth-$selectedYear.csv");

// Open output stream
$output = fopen('php://output', 'w');

// Add section headers and data
// 1. Monthly Report Header
fputcsv($output, ['MONTHLY REPORT', "Month: $selectedMonth", "Year: $selectedYear"]);
fputcsv($output, []); // Blank line

// 2. Statistics Section
fputcsv($output, ['STATISTICS']);
fputcsv($output, ['Metric', 'Value']);
foreach ($statistics as $key => $value) {
    fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
}

// 3. Total Salaries Section
fputcsv($output, []);
fputcsv($output, ['TOTAL SALARIES']);
fputcsv($output, ['Total Salaries', number_format($totalSalaries, 2)]);

// 4. Total Revenue Section
fputcsv($output, []);
fputcsv($output, ['TOTAL REVENUE']);
fputcsv($output, ['Total Revenue', number_format($totalRevenue, 2)]);

// 5. Guests By Country Section
fputcsv($output, []);
fputcsv($output, ['GUESTS BY COUNTRY']);
fputcsv($output, ['Country', 'Number of Guests']);
foreach ($guestsByCountry as $row) {
    fputcsv($output, [$row['country'], $row['total_guests']]);
}

// 6. Guests Percentage By Country Section
fputcsv($output, []);
fputcsv($output, ['GUESTS PERCENTAGE BY COUNTRY']);
fputcsv($output, ['Country', 'Percentage']);
foreach ($guestsPercentageByCountry as $row) {
    fputcsv($output, [$row['country'], $row['percentage'] . '%']);
}

// Close output stream
fclose($output);
exit;
?>
