<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($firstname) && !empty($lastname) && !empty($phone) && !empty($email) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, phone, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $phone, $email, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: ../index.php?success=Registration successful! Please log in.");
        } else {
            header("Location: ../views/register.php?error=Error registering user.");
        }
        $stmt->close();
    } else {
        header("Location: ../views/register.php?error=All fields are required!");
    }
}
?>
