<?php
include './db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = $_POST['booking_id'];
    $status = $_POST['status'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];

    $sql = "UPDATE bookings 
            SET status = ?, check_in = ?, check_out = ? 
            WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $status, $checkIn, $checkOut, $bookingId);

    if ($stmt->execute()) {
        header("Location: ../views/admin/bookings.php?success=Booking updated successfully");
    } else {
        header("Location: ../views/admin/rooms.php?error=Failed to update booking");
    }
    $stmt->close();
    $conn->close();
}
?>
