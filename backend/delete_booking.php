<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    include "./db_connection.php";

    // Sanitize the booking_id
    $booking_id = intval($_POST["delete"]);

    try {
        // Start transaction
        $conn->begin_transaction();

        // Get the guest_id associated with the booking
        $stmt = $conn->prepare("SELECT guest_id FROM bookings WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $guest_id = $result->fetch_assoc()['guest_id'];

        if (!$guest_id) {
            throw new Exception("Guest ID not found for the specified booking ID.");
        }

        // Delete from bookings table
        $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        // Delete from kin table
        $stmt = $conn->prepare("DELETE FROM kin WHERE guest_id = ?");
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();

        // Delete from guests table
        $stmt = $conn->prepare("DELETE FROM guests WHERE guest_id = ?");
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        header("Location: ../views/admin/bookings.php?success=Booking, guest, and next of kin deleted successfully.");
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        header("Location: ../views/admin/bookings.php?error=" . urlencode($e->getMessage()));
    }

    $conn->close();
    exit;
} else {
    header("Location: ../views/admin/bookings.php");
    exit;
}
?>
