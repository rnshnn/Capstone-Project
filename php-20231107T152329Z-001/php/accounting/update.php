<?php
session_start();
require_once '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $requestId = $_POST['request_id']; // Assuming you've sanitized the input
    $newMessage = $_POST['S_MES']; // You need to capture the selected reason here
    $newPrice = $_POST['P_price']; // Get the entered price (without currency symbol)

    // Add the peso sign (₱) to the price
    $newPriceWithCurrency = '₱' . $newPrice;

    $statusStu = "unseen";

    // Update the S_MES and P_price fields
    $updateQuery = "UPDATE payment SET S_MES = ?, P_price = ?, S_STA_STU = ? WHERE P_ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $newMessage, $newPriceWithCurrency, $statusStu, $requestId);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the original page
    header('Location: ../../accounting/payment?success=Update successfully');
    exit();
}
?>

