<?php
session_start();
require_once '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];

    // Define any other necessary variables
    // $verify, $disable, $status, $sms, etc.

    // Prepare and execute the DELETE query
    $deleteQuery = "DELETE FROM register WHERE R_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $requestId);

    if ($stmt->execute()) {
        header('Location: ../../admin/request?success=Account declined successfully');
        exit();
    } else {
        header('Location: ../../admin/request?error=Failed to Declined account');
        exit();
    }

    $stmt->close();
} else {
    header('Location: ../../admin/request?error=Invalid request');
    exit();
}
?>