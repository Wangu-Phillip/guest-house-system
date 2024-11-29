<!-- update_room.php -->
<?php
@include "./db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['room_id']);
    $roomType = mysqli_real_escape_string($conn, $_POST['roomtype']);
    $roomNumber = mysqli_real_escape_string($conn, $_POST['roomnumber']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $query = "UPDATE rooms SET room_Number = '$roomNumber', room_Type = '$roomType', price = '$price' WHERE room_id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: ../views/admin/rooms.php?success=Room details updated successfully.");
        exit();
    } else {
        header("Location: ../views/admin/rooms.php?error=Error updating room: " . mysqli_error($conn));
        exit();
    }

    mysqli_close($conn);
}
?>