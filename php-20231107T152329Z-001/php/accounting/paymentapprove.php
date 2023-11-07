<?php
session_start();
require_once '../dbcon.php';

if (isset($_POST['paymentapprove'])) {
    $P_ID = (int) $_POST['paymentapprove'];
    $newMessage = "waiting";

    $statusReg = "unseen"; 
    $statusStu = "unseen"; 
    $panelName = "on precess request";


    // Update the S_MES field to "waiting"
    $updateSmesQuery = "UPDATE payment SET S_MES = ? WHERE P_ID = ?";
    $stmt = $conn->prepare($updateSmesQuery);
    $stmt->bind_param("si", $newMessage, $P_ID);
    
    if ($stmt->execute()) {
        // Continue with the process of transferring data to the process table
        
        $selectQuery = "SELECT * FROM payment WHERE P_ID = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("i", $P_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $insertQuery = "INSERT INTO process (O_ID, O_FULL, O_EMAIL, O_COU, O_YEAR, O_SID, O_NUM, O_ADD, O_UND, O_COM, O_POS, O_documentType, O_numCopies, O_documentType_2, O_numCopies_2, O_documentType_3, O_numCopies_3, O_firstRequest, O_price, O_fileInput, O_CODE, S_MES, S_STA_STU, S_STA_REG, pannel, O_ASIG, O_DEL, O_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("isssssssssssssssssssssssssss", $row['P_ID'], $row['P_FULL'], $row['P_EMAIL'], $row['P_COU'], $row['P_YEAR'], $row['P_SID'], $row['P_NUM'], $row['P_ADD'], $row['P_UND'], $row['P_COM'], $row['P_POS'], $row['P_documentType'], $row['P_numCopies'], $row['P_documentType_2'], $row['P_numCopies_2'], $row['P_documentType_3'], $row['P_numCopies_3'], $row['P_firstRequest'], $row['P_price'], $row['P_fileInput'], $row['P_CODE'], $newMessage, $statusReg, $statusStu, $panelName, $row['P_ASIG'], $row['P_DEL'], $row['P_DATE']);
            $insertResult = $stmt->execute();
            
            if ($insertResult) {
                $deleteQuery = "DELETE FROM payment WHERE P_ID = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->bind_param("i", $P_ID);
                $deleteResult = $stmt->execute();
                
                if ($deleteResult) {
                    header('Location: ../../accounting/payment?success=Payment approved successfully');
                    exit();
                } else {
                    header('Location: ../../accounting/payment?error=Deletion error after approval');
                    exit();
                }
            } else {
                header('Location: ../../accounting/payment?error=Approval inserting error');
                exit();
            }
        } else {
            header('Location: ../../accounting/payment?error=Payment not found');
            exit();
        }
    } else {
        header('Location: ../../accounting/payment?error=Updating S_MES error');
        exit();
    }
} else {
    header('Location: ../../accounting/payment?error=Invalid request');
    exit();
}
?>
