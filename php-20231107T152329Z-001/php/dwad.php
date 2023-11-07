<?php


$sql = "SELECT * FROM request WHERE S_MES IN (?, ?) AND S_STUN IS NOT NULL AND TIMESTAMPDIFF(SECOND, S_STUN, NOW()) > ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssi", $allowedS_MES[1], $allowedS_MES[2], $expiryTimeframe);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);


  $insertQuery = "INSERT INTO payment (P_ID, P_FULL, P_EMAIL, P_COU, P_YEAR, P_SID, P_NUM, P_ADD, P_UND, P_COM, P_POS, P_documentType, P_numCopies, P_firstRequest, P_price, P_fileInput, P_CODE, S_MES, P_ASIG, P_DEL, P_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $insertQuery);
  mysqli_stmt_bind_param($stmt, "issssssssssssssssssss", $row['S_ID'], $row['S_FULL'], $row['S_EMAIL'], $row['S_COU'], $row['S_YEAR'], $row['S_SID'], $row['S_NUM'], $row['S_ADD'], $row['S_UND'], $row['S_COM'], $row['S_POS'], $row['documentType'], $row['numCopies'], $row['firstRequest'], $row['price'], $row['fileInput'], $row['S_CODE'], $newMessage, $row['S_ASIG'], $row['S_DEL'], $row['S_DATE']);
  $insertResult = mysqli_stmt_execute($stmt);

  if ($insertResult) {
      $deleteQuery = "DELETE FROM request WHERE S_MES IN (?, ?) AND S_STUN IS NOT NULL AND TIMESTAMPDIFF(SECOND, S_STUN, NOW()) > ?";
      $stmt = mysqli_prepare($conn, $deleteQuery);
      mysqli_stmt_bind_param($stmt, "ssi", $allowedS_MES[1], $allowedS_MES[2], $expiryTimeframe);
      $deleteResult = mysqli_stmt_execute($stmt);

  }



session_start();
if (isset($_SESSION['R_VERIFIED']) && isset($_SESSION['R_FULL']) && isset($_SESSION['R_EMAIL']) && isset($_SESSION['R_FIRST']) && isset($_SESSION['R_MIDD'])) {

    include "../php/dbcon.php";

    $expiryTimeframe = 440000;

    // Define an array of allowed values for S_MES
    $allowedS_MES = ['invalid pdf file', 'luck of information file'];


    $sql = "SELECT * FROM request WHERE S_MES IN (?, ?) AND S_STUN IS NOT NULL AND TIMESTAMPDIFF(SECOND, S_STUN, NOW()) > ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $allowedS_MES[1], $allowedS_MES[2], $expiryTimeframe);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    
      $insertQuery = "INSERT INTO payment (P_ID, P_FULL, P_EMAIL, P_COU, P_YEAR, P_SID, P_NUM, P_ADD, P_UND, P_COM, P_POS, P_documentType, P_numCopies, P_firstRequest, P_price, P_fileInput, P_CODE, S_MES, P_ASIG, P_DEL, P_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $insertQuery);
      mysqli_stmt_bind_param($stmt, "issssssssssssssssssss", $row['S_ID'], $row['S_FULL'], $row['S_EMAIL'], $row['S_COU'], $row['S_YEAR'], $row['S_SID'], $row['S_NUM'], $row['S_ADD'], $row['S_UND'], $row['S_COM'], $row['S_POS'], $row['documentType'], $row['numCopies'], $row['firstRequest'], $row['price'], $row['fileInput'], $row['S_CODE'], $newMessage, $row['S_ASIG'], $row['S_DEL'], $row['S_DATE']);
      $insertResult = mysqli_stmt_execute($stmt);
    
      if ($insertResult) {
          $deleteQuery = "DELETE FROM request WHERE S_MES IN (?, ?) AND S_STUN IS NOT NULL AND TIMESTAMPDIFF(SECOND, S_STUN, NOW()) > ?";
          $stmt = mysqli_prepare($conn, $deleteQuery);
          mysqli_stmt_bind_param($stmt, "ssi", $allowedS_MES[1], $allowedS_MES[2], $expiryTimeframe);
          $deleteResult = mysqli_stmt_execute($stmt);
    
      }
    
    // Use prepared statement to delete records
    $deleteExpiredQuery = "DELETE FROM request WHERE S_MES IN (?, ?) AND S_STUN IS NOT NULL AND TIMESTAMPDIFF(SECOND, S_STUN, NOW()) > ?";
    $stmt = mysqli_prepare($conn, $deleteExpiredQuery);

    if ($stmt) {
        // Bind parameters and execute the query
        mysqli_stmt_bind_param($stmt, "ssi", $allowedS_MES[1], $allowedS_MES[2], $expiryTimeframe);
        mysqli_stmt_execute($stmt);

        // Check if any rows were affected
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header("Location: pending?error=Request declined due to several days of no response to your request.");
            exit;
        } else {
        
        }

                mysqli_stmt_close($stmt);
            } else {

            }

        
            mysqli_close($conn);
        } else {
        
            header("location: ../signin");
            exit();
        }
        





<?php
include "../php/dbcon.php";
session_start();
if (isset($_SESSION['R_VERIFIED']) && isset($_SESSION['R_FULL']) && isset($_SESSION['R_EMAIL']) && isset($_SESSION['R_FIRST']) && isset($_SESSION['R_MIDD'])) {

    $expiryTimeframe = 20;
    $allowedS_MES = ['invalid pdf file', 'luck of information file'];
    $newMessage = "Request declined due to several days of no response to request."; 

    // Use prepared statement to select records
    $selectQuery = "SELECT * FROM request WHERE S_MES IN (?, ?) AND S_STUN IS NOT NULL AND TIMESTAMPDIFF(SECOND, S_STUN, NOW()) > ?";
    $stmtSelect = mysqli_prepare($conn, $selectQuery);
    mysqli_stmt_bind_param($stmtSelect, "ssi", $allowedS_MES[0], $allowedS_MES[1], $expiryTimeframe);
    mysqli_stmt_execute($stmtSelect);
    $result = mysqli_stmt_get_result($stmtSelect);

    // Fetch the selected record
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Insert the fetched record into the payment table
        $insertQuery = "INSERT INTO decline (C_ID, C_FULL, C_COU, C_YEAR, C_SID, C_documentType, C_numCopies, C_firstRequest, C_price, C_CODE, S_MES, C_ASIG, C_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = mysqli_prepare($conn, $insertQuery);

        if ($stmtInsert) {
            mysqli_stmt_bind_param($stmtInsert, "issssssssssss", $row['S_ID'], $row['S_FULL'], $row['S_COU'], $row['S_YEAR'], $row['S_SID'], $row['documentType'], $row['numCopies'], $row['firstRequest'], $row['price'], $row['S_CODE'], $newMessage, $row['S_ASIG'], $row['S_DEL'], $row['S_DATE']);
            $insertResult = mysqli_stmt_execute($stmtInsert);

            if ($insertResult) {
                // Use prepared statement to delete the selected record
                $deleteQuery = "DELETE FROM request WHERE S_MES IN (?, ?) AND S_STUN IS NOT NULL AND TIMESTAMPDIFF(SECOND, S_STUN, NOW()) > ?";
                $stmtDelete = mysqli_prepare($conn, $deleteQuery);
                mysqli_stmt_bind_param($stmtDelete, "ssi", $allowedS_MES[0], $allowedS_MES[1], $expiryTimeframe);
                $deleteResult = mysqli_stmt_execute($stmtDelete);

                if ($deleteResult) {
                    header('Location: pending?error=Request declined due to several days of no response to your request.');
                    exit();
                } else {
                    header('Location: pending?error=Deletion error after approval');
                    exit();
                }
            } else {
                header('Location: pending?error=Payment insertion error');
                exit();
            }
        }
    }
    
    mysqli_close($conn);
} else {
    header("location: ../signin");
    exit();
}
?>
