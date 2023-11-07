<?php
include 'dbcon.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset(
        $_POST['documentType'],
        $_POST['documentType_2'],
        $_POST['documentType_3'],
        $_POST['numCopies'],
        $_POST['numCopies_2'],
        $_POST['numCopies_3'],
        $_POST['firstRequest'],
        $_POST['price'],
        $_POST['S_FULL'],
        $_POST['S_EMAIL'],
        $_POST['S_DEL'],
        $_POST['S_SID'],
        $_POST['S_ADD'],
        $_POST['S_COU'],
        $_POST['S_NUM'],
        $_POST['S_YEAR'],
        $_POST['S_UND'],
        $_POST['S_POS'],
        $_POST['S_COM']
    
 
        )) {

        $documentType = $_POST['documentType'];
        $documentType_2 = $_POST['documentType_2'];
        $documentType_3 = $_POST['documentType_3'];
        $numCopies = $_POST['numCopies'];
        $numCopies_2 = $_POST['numCopies_2'];
        $numCopies_3 = $_POST['numCopies_3'];
        $firstRequest = $_POST['firstRequest'];
        $price = $_POST['price'];
        $S_FULL = $_POST['S_FULL'];
        $S_EMAIL = $_POST['S_EMAIL'];
        $S_DEL = $_POST['S_DEL'];
        $S_SID = $_POST['S_SID'];
        $S_ADD = $_POST['S_ADD'];
        $S_COU = $_POST['S_COU'];
        $S_NUM = $_POST['S_NUM'];
        $S_YEAR = $_POST['S_YEAR'];
        $S_UND = $_POST['S_UND'];
        $S_POS = $_POST['S_POS'];
        $S_COM = $_POST['S_COM'];

        $newMessage = "waiting"; 
        $statusReg = "unseen"; 
        $panelName = "pending request";

        $sqlCheckRequests = "SELECT COUNT(*) AS num_requests FROM request WHERE S_FULL = ?";
        $stmtCheckRequests = $conn->prepare($sqlCheckRequests);
        $stmtCheckRequests->bind_param("s", $S_FULL);
        $stmtCheckRequests->execute();
        $resultCheckRequests = $stmtCheckRequests->get_result();
        $rowCheckRequests = $resultCheckRequests->fetch_assoc();
        $numRequests = $rowCheckRequests['num_requests'];

        if ($numRequests >= 5) {
            header("Location: ../student/request?error=You've reached the request limit");
            exit;
        }

        $sCode = rand(1000000000, 9999999999);

        if ($S_DEL === 'deliver') {

            $sqlCheckAddress = "SELECT R_ADD, R_STRE, R_BRGY, R_MUNI, R_CITY, R_CON FROM register WHERE R_FULL = ?";
            $stmtCheckAddress = $conn->prepare($sqlCheckAddress);
            $stmtCheckAddress->bind_param("s", $_SESSION['R_FULL']);
            $stmtCheckAddress->execute();
            $resultCheckAddress = $stmtCheckAddress->get_result();
            $rowCheckAddress = $resultCheckAddress->fetch_assoc();
        
            if ($rowCheckAddress && empty($rowCheckAddress['R_ADD']) && empty($rowCheckAddress['R_STRE']) &&
                empty($rowCheckAddress['R_BRGY']) && empty($rowCheckAddress['R_MUNI']) &&
                empty($rowCheckAddress['R_CITY']) && empty($rowCheckAddress['R_CON'])) {
                
                header("Location: ../student/request?error=Failed to sent, please go to your account and update your information.");
                exit;
            }
        }

        if ($firstRequest === 'yes') {

            $sqlCheckFirstRequest = "SELECT R_RULL FROM register WHERE R_FULL = ?";
            $stmtCheckFirstRequest = $conn->prepare($sqlCheckFirstRequest);
            $stmtCheckFirstRequest->bind_param("s", $_SESSION['R_FULL']);
            $stmtCheckFirstRequest->execute();
            $resultCheckFirstRequest = $stmtCheckFirstRequest->get_result();
            $rowCheckFirstRequest = $resultCheckFirstRequest->fetch_assoc();
        
            if ($rowCheckFirstRequest && $rowCheckFirstRequest['R_RULL'] === 'already used') {
                header("Location: ../student/request?error=1st request already used");
                exit;
            } else {
                // Mark the user's first request as used
                $sqlUpdateR_RULL = "UPDATE register SET R_RULL = 'already used' WHERE R_FULL = ?";
                $stmtUpdateR_RULL = $conn->prepare($sqlUpdateR_RULL);
                $stmtUpdateR_RULL->bind_param("s", $_SESSION['R_FULL']);
                $stmtUpdateR_RULL->execute();
            }
        }

        // Randomly assign a registrar's name to S_ASIG from the account table
        $sqlGetRegistrarName = "SELECT R_FULL FROM register WHERE R_VERIFIED = 3 ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sqlGetRegistrarName);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sAsig = $row['R_FULL'];
        } else {
            header("Location: ../student/request?error=assign default");
            exit;
        }

        if ($_FILES['fileInput']['error'] === 0) {
            $proof_img_name = $_FILES['fileInput']['name'];
            $proof_tmp_name = $_FILES['fileInput']['tmp_name'];

            // Set new file name and upload path
            $proof_img_ex = pathinfo($proof_img_name, PATHINFO_EXTENSION);
            $proof_img_ex_to_lc = strtolower($proof_img_ex);
            $new_proof_img_name = uniqid($S_FULL, true) . '.' . $proof_img_ex_to_lc;
            $proof_img_upload_path = '../upload/' . $new_proof_img_name;


            if (move_uploaded_file($proof_tmp_name, $proof_img_upload_path)) {

                $sql = "INSERT INTO request (documentType, numCopies, documentType_2, numCopies_2, documentType_3, numCopies_3, firstRequest, price, S_FULL, S_EMAIL, S_MES, S_STA_REG, pannel, S_DEL, S_SID, S_ADD, S_COU, S_NUM, S_YEAR, S_UND, S_POS, S_COM, fileInput, S_CODE, S_ASIG) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sisssssssssssssssssssssss", $documentType, $numCopies, $documentType_2, $numCopies_2, $documentType_3, $numCopies_3, $firstRequest, $price, $S_FULL, $S_EMAIL, $newMessage, $statusReg, $panelName, $S_DEL, $S_SID, $S_ADD, $S_COU, $S_NUM, $S_YEAR, $S_UND, $S_POS, $S_COM, $new_proof_img_name, $sCode, $sAsig);
                
                if ($stmt->execute()) {
                    header("Location: ../student/request?success=Your request has been sent successfully");
                    exit;
                } else {
                    header("Location: ../student/request?error=Request not sent");
                    exit;
                }
            } else {
                header("Location: ../student/request?error=File upload error");
                exit;
            }
        } else {

            $sql = "INSERT INTO request (documentType, numCopies, documentType_2, numCopies_2, documentType_3, numCopies_3, firstRequest, price, S_FULL, S_EMAIL, S_MES, S_STA_REG, pannel, S_DEL, S_SID, S_ADD, S_COU, S_NUM, S_YEAR, S_UND, S_POS, S_COM, S_CODE, S_ASIG) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sissssssssssssssssssssss", $documentType, $numCopies, $documentType_2, $numCopies_2, $documentType_3, $numCopies_3, $firstRequest, $price, $S_FULL, $S_EMAIL, $newMessage, $statusReg, $panelName, $S_DEL, $S_SID, $S_ADD, $S_COU, $S_NUM, $S_YEAR, $S_UND, $S_POS, $S_COM, $sCode, $sAsig);
            
            if ($stmt->execute()) {
                header("Location: ../student/request?success=Your request has been sent successfully");
                exit;
            } else {
                header("Location: ../student/request?error=Request not sent");
                exit;
            }
        }
    } else {
        header("Location: ../student/request?error=Missing required fields");
        exit;
    }
}
?>