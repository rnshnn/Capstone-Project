<?php
session_start();
require_once '../dbcon.php';

if (isset($_POST['release'], $_POST['L_DEL'], $_POST['L_REA'])) {
    $L_ID = (int) $_POST['release'];
    $L_DEL = $_POST['L_DEL'];
    $L_REA = $_POST['L_REA'];

    $statusStu = "unseen"; 
    $panelName = "done request";

    $updateSmesQuery = "UPDATE releasing SET L_REA = ? WHERE L_ID = ?";
    $stmt = $conn->prepare($updateSmesQuery);
    $stmt->bind_param("si", $L_REA, $L_ID);
    $stmt->execute();

    if ($L_DEL === 'pick up') {
        $sqlUpdateS_MES = "UPDATE releasing SET S_MES = 'your request has been released' WHERE L_ID = ?";
        $stmtUpdateS_MES = $conn->prepare($sqlUpdateS_MES);
        $stmtUpdateS_MES->bind_param("i", $L_ID);
        $stmtUpdateS_MES->execute();
    } elseif ($L_DEL === 'deliver') {
        $sqlUpdateS_MES = "UPDATE releasing SET S_MES = 'your request is shipping out' WHERE L_ID = ?";
        $stmtUpdateS_MES = $conn->prepare($sqlUpdateS_MES);
        $stmtUpdateS_MES->bind_param("i", $L_ID);
        $stmtUpdateS_MES->execute();
    }

    $sql = "SELECT * FROM releasing WHERE L_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $L_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $insertQuery = "INSERT INTO done (D_ID, D_FULL, D_EMAIL, D_COU, D_YEAR, D_SID, D_NUM, D_ADD, D_UND, D_COM, D_POS, D_documentType, D_numCopies, D_documentType_2, D_numCopies_2, D_documentType_3, D_numCopies_3, D_firstRequest, D_price, D_fileInput, D_CODE, S_MES, S_STA_STU, pannel, D_ASIG, D_REA, D_DEL, D_DATE, D_DATE_DONE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($insertQuery);
    $stmtInsert->bind_param("issssssssssssssssssssssssssss", $row['L_ID'], $row['L_FULL'], $row['L_EMAIL'], $row['L_COU'], $row['L_YEAR'], $row['L_SID'], $row['L_NUM'], $row['L_ADD'], $row['L_UND'], $row['L_COM'], $row['L_POS'], $row['L_documentType'], $row['L_numCopies'], $row['L_documentType_2'], $row['L_numCopies_2'], $row['L_documentType_3'], $row['L_numCopies_3'], $row['L_firstRequest'], $row['L_price'], $row['L_fileInput'], $row['L_CODE'], $row['S_MES'], $statusStu, $panelName, $row['L_ASIG'], $row['L_REA'], $row['L_DEL'], $row['L_DATE'], $row['L_DATE_DONE']);
    $insertResult = $stmtInsert->execute();

    if ($insertResult) {
        $deleteQuery = "DELETE FROM releasing WHERE L_ID = ?";
        $stmtDelete = $conn->prepare($deleteQuery);
        $stmtDelete->bind_param("i", $L_ID);
        $deleteResult = $stmtDelete->execute();

        if ($deleteResult) {
            header('Location: ../../registrar/releasing?success=Request process finish successfully');
            exit();
        } else {
            header('Location: ../../registrar/releasing?error=Deletion error after approval');
            exit();
        }
    } else {
        header('Location: ../../registrar/releasing?error=Payment insertion error');
        exit();
    }
}
