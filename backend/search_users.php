<?php
include './db_connection.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : "";

$sql = "SELECT user_id, CONCAT(firstname, ' ', lastname) AS employee_name, email, phone, role, status, salary 
        FROM users";

if (!empty($query)) {
    $sql .= " WHERE firstname LIKE ? OR lastname LIKE ? OR CONCAT(firstname, ' ', lastname) LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($query)) {
    $search = "%" . $query . "%";
    $stmt->bind_param("sss", $search, $search, $search);
}

$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$response = [
    "count" => count($users),
    "users" => $users,
];

header("Content-Type: application/json");
echo json_encode($response);
?>
