<?php
session_start();
require_once '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id']; 
    $verify = "3"; 
    $disable = "Enable"; 

    // Check if the user is an admin before disabling the account
    $checkAdminQuery = "SELECT R_POS FROM register WHERE R_ID = ?";
    $stmtCheckAdmin = $conn->prepare($checkAdminQuery);
    $stmtCheckAdmin->bind_param("i", $requestId);
    $stmtCheckAdmin->execute();
    $stmtCheckAdmin->bind_result($userPos);
    $stmtCheckAdmin->fetch();
    $stmtCheckAdmin->close();

    if ($userPos === "Admin") {
        header('Location: ../../admin/user?error=Cannot Enable admin account');
        exit();
    }

    // Update the user's account if it's not an admin account
    $updateQuery = "UPDATE register SET R_VERIFIED = ?, R_SMS = ? WHERE R_ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $verify, $disable, $requestId);
    
    if ($stmt->execute()) {
        header('Location: ../../admin/user?success=Enable successfully');
        exit();
    } else {
        header('Location: ../../admin/user?error=Failed to disable');
        exit();
    }

    $stmt->close();
} else {
    header('Location: ../../admin/user?error=Invalid request');
    exit();
}
?>
