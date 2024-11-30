<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roomType = $_POST['roomtype'];
    $roomNo = $_POST['roomnumber'];
    $price = $_POST['price'];

    if (!empty($roomType) && !empty($roomNo) && !empty($price)) {
        
        $stmt = $conn->prepare("INSERT INTO rooms (room_Number, room_Type, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $roomNo, $roomType, $price);

        if ($stmt->execute()) {
            header("Location: ../views/admin/rooms.php?success=Room details saved successfully.");
        } else {
            header("Location: ../views/admin/rooms.php?error=Error saving new room.");
        }
        $stmt->close();
    } else {
        header("Location: ../views/admin/rooms.php?error=All fields are required!");
    }
}
?>
