<?php
session_start();
require_once '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id']; 
    $verify = "6"; 
    $disable = "Disable"; 

    $updateQuery = "UPDATE register SET R_VERIFIED = ?, R_SMS = ? WHERE R_ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $verify, $disable, $requestId);
    
    if ($stmt->execute()) {
        header('Location: ../../admin/student?success=Disabled successfully');
        exit();
    } else {
        header('Location: ../../admin/student?error=Failed to disable');
        exit();
    }

    $stmt->close();
} else {
    header('Location: ../../admin/student?error=Invalid request');
    exit();
}
?>
