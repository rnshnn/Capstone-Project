<?php
session_start();
require_once '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id']; // Assuming you've sanitized the input
    $newMessage = $_POST['S_MES']; // You need to capture the selected reason here
    $statusStu = "unseen";
    // Update the S_MES field
    $updateSmesQuery = "UPDATE process SET S_MES = ?, S_STA_STU = ? WHERE O_ID = ?";
    $stmt = $conn->prepare($updateSmesQuery);
    $stmt->bind_param("ssi", $newMessage, $statusStu, $requestId);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the original page
    header('Location: ../../registrar/onprocess?success=Update successfully');
    exit();
}
?>