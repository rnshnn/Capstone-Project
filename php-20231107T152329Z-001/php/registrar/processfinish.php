<?php
session_start();
require_once '../dbcon.php';

if (isset($_POST['processfinish'], $_POST['O_DEL'])) {
    $O_ID = (int) $_POST['processfinish'];
    $O_DEL = $_POST['O_DEL'];

    $statusStu = "unseen"; 
    $panelName = "releasing request";

    if ($O_DEL === 'pick up') {
        // Mark the user's request as 'ready to pick up'
        $sqlUpdateS_MES = "UPDATE process SET S_MES = 'ready to pick up' WHERE O_ID = ?";
        $stmtUpdateS_MES = $conn->prepare($sqlUpdateS_MES);
        $stmtUpdateS_MES->bind_param("i", $O_ID);
        $stmtUpdateS_MES->execute();
    } elseif ($O_DEL === 'deliver') {
        // Mark the user's request as 'ready to ship out'
        $sqlUpdateS_MES = "UPDATE process SET S_MES = 'ready to ship' WHERE O_ID = ?";
        $stmtUpdateS_MES = $conn->prepare($sqlUpdateS_MES);
        $stmtUpdateS_MES->bind_param("i", $O_ID);
        $stmtUpdateS_MES->execute();
    }

    $sql = "SELECT * FROM process WHERE O_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $O_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    // Additional code here if needed

    $insertQuery = "INSERT INTO releasing (L_ID, L_FULL, L_EMAIL, L_COU, L_YEAR, L_SID, L_NUM, L_ADD, L_UND, L_COM, L_POS, L_documentType, L_numCopies, L_documentType_2, L_numCopies_2, L_documentType_3, L_numCopies_3, L_firstRequest, L_price, L_fileInput, L_CODE, S_MES, S_STA_STU, pannel, L_ASIG, L_DEL, L_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($insertQuery);
    $stmtInsert->bind_param("issssssssssssssssssssssssss", $row['O_ID'], $row['O_FULL'], $row['O_EMAIL'], $row['O_COU'], $row['O_YEAR'], $row['O_SID'], $row['O_NUM'], $row['O_ADD'], $row['O_UND'], $row['O_COM'], $row['O_POS'], $row['O_documentType'], $row['O_numCopies'], $row['O_documentType_2'], $row['O_numCopies_2'], $row['O_documentType_3'], $row['O_numCopies_3'], $row['O_firstRequest'], $row['O_price'], $row['O_fileInput'], $row['O_CODE'], $row['S_MES'], $statusStu, $panelName, $row['O_ASIG'], $row['O_DEL'], $row['O_DATE']);
    $insertResult = $stmtInsert->execute();

    if ($insertResult) {
        $deleteQuery = "DELETE FROM process WHERE O_ID = ?";
        $stmtDelete = $conn->prepare($deleteQuery);
        $stmtDelete->bind_param("i", $O_ID);
        $deleteResult = $stmtDelete->execute();

        if ($deleteResult) {
            header('Location: ../../registrar/onprocess?success=Request process finish successfully');
            exit();
        } else {
            header('Location: ../../registrar/onprocess?error=Deletion error after approval');
            exit();
        }
    } else {
        header('Location: ../../registrar/onprocess?error=Payment insertion error');
        exit();
    }
}
?>
