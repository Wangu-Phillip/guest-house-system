<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (!empty($email) && !empty($password)) {
        // Query the database for the user
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['role'] = $user['role'];
                $_SESSION['user'] = $user['user_id'];


                if ($_SESSION["role"] == "admin") {

                    $_SESSION['firstname'] = $user['firstname'];
                    $_SESSION['lastname'] = $user['lastname'];
                    $_SESSION["admin_email"] = $row["email"];
                    $_SESSION["admin_id"] = $user["user_id"];
        
                    header("Location: ../views/admin/dashboard.php?success=Welcome back, {$user['firstname']}!");
                    exit();
                } elseif ($_SESSION["role"] == "employee") {
        
                    $_SESSION['employeeFname'] = $user['firstname'];
                    $_SESSION['employeeLname'] = $user['lastname'];
                    $_SESSION["employee_email"] = $row["email"];
                    $_SESSION["employee_id"] = $user["user_id"];
        
                    header("Location: ../views/admin/bookings.php?success=Welcome back, {$user['firstname']}!");
                    exit();
                }

            } else {
                // Incorrect password
                header("Location: ../index.php?error=Invalid password.");
                exit();
            }
        } else {
            // User not found
            header("Location: ../index.php?error=No account found with this email.");
            exit();
        }
    } else {
        // Missing fields
        header("Location: ../index.php?error=Please fill in all fields.");
        exit();
    }
}
?>
