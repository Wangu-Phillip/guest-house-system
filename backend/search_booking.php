<?php
include './db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
    $search = mysqli_real_escape_string($conn, $_POST["search"]);

    // Correct the query to use WHERE clause before ORDER BY
    $sql = "SELECT 
            b.booking_id,
            b.date, 
            CONCAT(g.firstname, ' ', g.lastname) AS guest_name, 
            g.phone AS guest_number, 
            g.omang_id AS guest_id, 
            r.room_number, 
            r.price AS amount, 
            b.status, 
            b.check_in, 
            b.check_out 
        FROM bookings b
        LEFT JOIN guests g ON b.guest_id = g.guest_id
        LEFT JOIN rooms r ON b.room_id = r.room_id
        WHERE g.firstname LIKE '%$search%' OR g.lastname LIKE '%$search%'
        ORDER BY b.date DESC";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["guest_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["guest_number"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["guest_id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["room_number"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["amount"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["check_in"]) ?: '-' . "</td>";
            echo "<td>" . htmlspecialchars($row["check_out"]) ?: '-' . "</td>";
            echo "<td>";
            echo "<form method='post' action='../../backend/delete_booking.php' style='display:inline;'>";
            echo "<input type='hidden' name='delete' value='" . htmlspecialchars($row["booking_id"]) . "'>";
            echo "<button class='btn btn-danger btn-sm' type='submit'><i class='bi bi-trash'></i></button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10' class='text-center text-muted'>No results found</td></tr>";
    }

    mysqli_close($conn);
    exit;
}
?>
