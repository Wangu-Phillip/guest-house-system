<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];
    $password = $_POST['password'];

    if (!empty($firstname) && !empty($lastname) && !empty($phone) && !empty($email) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, phone, email, role, password, salary) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstname, $lastname, $phone, $email, $role, $hashedPassword, $salary);

        if ($stmt->execute()) {
            header("Location: ../views/admin/users.php?success=User details saved successfully.");
        } else {
            header("Location: ../views/admin/users.php?error=Error saving new user.");
        }
        $stmt->close();
    } else {
        header("Location: ../views/admin/users.php?error=All fields are required!");
    }
}
?>
