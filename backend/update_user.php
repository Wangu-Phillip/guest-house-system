<!-- update_user.php -->
<?php
@include "./db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);

    $query = "UPDATE users SET firstname = '$firstname', lastname = '$lastname', phone = '$phone', email = '$email', role = '$role', salary = '$salary', status = '$status' WHERE user_id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: ../views/admin/users.php?success=User details updated successfully.");
        exit();
    } else {
        header("Location: ../views/admin/users.php?error=Error updating user: " . mysqli_error($conn));
        exit();
    }

    mysqli_close($conn);
}
?>