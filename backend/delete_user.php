<?php
include 'db_connection.php';

header('Content-Type: application/json');

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_id'])) {
    $user_id = $data['user_id'];

    // Prepare and execute the DELETE query
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting user."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid user ID."]);
}

$conn->close();
?>
