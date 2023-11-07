<?php
session_start();
require_once '../dbcon.php';

if (isset($_POST['pending'])) {
    $S_ID = (int) $_POST['pending'];
    $newMessage = "send proof of payment"; 

    $statusStu = "unseen"; 
    $panelName = "payment";

    $sql = "SELECT * FROM request WHERE S_ID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $S_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $insertQuery = "INSERT INTO payment (P_ID, P_FULL, P_EMAIL, P_COU, P_YEAR, P_SID, P_NUM, P_ADD, P_UND, P_COM, P_POS, P_documentType, P_numCopies, P_documentType_2, P_numCopies_2, P_documentType_3, P_numCopies_3, P_firstRequest, P_price, P_fileInput, P_CODE, S_MES, S_STA_STU, pannel, P_ASIG, P_DEL, P_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "issssssssssssssssssssssssss", $row['S_ID'], $row['S_FULL'], $row['S_EMAIL'], $row['S_COU'], $row['S_YEAR'], $row['S_SID'], $row['S_NUM'], $row['S_ADD'], $row['S_UND'], $row['S_COM'], $row['S_POS'], $row['documentType'], $row['numCopies'], $row['documentType_2'], $row['numCopies_2'], $row['documentType_3'], $row['numCopies_3'], $row['firstRequest'], $row['price'], $row['fileInput'], $row['S_CODE'], $newMessage, $statusStu, $panelName, $row['S_ASIG'], $row['S_DEL'], $row['S_DATE']);
    $insertResult = mysqli_stmt_execute($stmt);

    if ($insertResult) {
        $deleteQuery = "DELETE FROM request WHERE S_ID = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $S_ID);
        $deleteResult = mysqli_stmt_execute($stmt);

        if ($deleteResult) {
            header('Location: ../../registrar/pending?success=Request approved successfully');
            exit();
        } else {
            header('Location: ../../registrar/pending?error=Deletion error after approval');
            exit();
        }
    } else {
        header('Location: ../../registrar/pending?error=Payment insertion error');
        exit();
    }
}
?>