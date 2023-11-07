<?php
session_start();
require_once '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id']; 
    $status = "Not Active";
    $verify = "approve"; 
    $disable = "Enable"; 
    $sms = "Alumni "; 

    // Update the user's account if it's not an admin account
    $updateQuery = "UPDATE register SET R_RORA = ?, R_SMS = ?, R_STATUS = ?, R_STU_POS = ? WHERE R_ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $verify, $disable, $status, $sms, $requestId);
    
    if ($stmt->execute()) {
        header('Location: ../../admin/request?success=Approve account successfully');
        exit();
    } else {
        header('Location: ../../admin/request?error=Failed to Approve');
        exit();
    }

    $stmt->close();
} else {
    header('Location: ../../admin/request?error=Invalid request');
    exit();
}
?>