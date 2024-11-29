<!-- delete_rooms.php -->

<?php

session_start();

if (!isset($_SESSION["admin_name"])) {
    header("location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    include "./db_connection.php";

    $id = mysqli_real_escape_string($conn, $_POST["delete"]);

    // Delete the user from the database
    $sql = "DELETE FROM rooms WHERE room_id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/admin/rooms.php?success=Room deleted successfully.");
    } else {
        $error_message = $conn->error;
        header("Location: ../views/admin/rooms.php?error=Error deleting room: $error_message");
    }

    // Close the database connection
    $conn->close();
    exit;
} else {
    header("Location: ../views/admin/rooms.php");
    exit;
}
?>