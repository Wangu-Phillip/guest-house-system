<?php
include './db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();

    try {
        $created_at = date('Y-m-d H:i:s');

        // Save Guest Details
        $stmt = $conn->prepare("
            INSERT INTO guests (firstname, lastname, omang_id, phone, address, company, email, citizenship, country, car_registration_no, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssssssss",
            $_POST['firstname'], 
            $_POST['lastname'], 
            $_POST['omang'], 
            $_POST['phone'], 
            $_POST['address'], 
            $_POST['company'], 
            $_POST['email'], 
            $_POST['citizenship'], 
            $_POST['country'], 
            $_POST['car_registration_no'], 
            $created_at
        );
        $stmt->execute();
        $guest_id = $stmt->insert_id;

        // Save Next of Kin Details
        $stmt = $conn->prepare("
            INSERT INTO kin (guest_id, firstname, lastname, address, cell, phone, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issssss",
            $guest_id, 
            $_POST['kin_firstname'], 
            $_POST['kin_lastname'], 
            $_POST['kin_address'], 
            $_POST['kin_cell'], 
            $_POST['kin_phone'], 
            $_POST['kin_email']
        );
        $stmt->execute();

        // Save Booking Details
        $stmt = $conn->prepare("
            INSERT INTO bookings (guest_id, room_id, price, date, status, payment_method, check_in, check_out, number_of_guests, number_of_nights, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iissssssiss",
            $guest_id, 
            $_POST['roomNo'], 
            $_POST['amount'], 
            $_POST['datebooked'], 
            $_POST['status'], 
            $_POST['payment_method'], 
            $_POST['check_in'], 
            $_POST['check_out'], 
            $_POST['number_of_guests'], 
            $_POST['number_of_nights'], 
            $created_at
        );
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        // Redirect with success message
        header("Location: ../views/admin/bookings.php?success=Booking+created+successfully");
    } catch (Exception $e) {
        $conn->rollback();

        // Redirect with error message
        header("Location: ../views/admin/bookings.php?error=" . urlencode($e->getMessage()));
    }
}
?>
