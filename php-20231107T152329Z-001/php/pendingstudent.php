<?php
session_start();
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id']; // Assuming you've sanitized the input
    $newMessage = "already update"; // You need to define the new message here
    $statusReg = "unseen";
    // Update the S_MES field
    $updateSmesQuery = "UPDATE request SET S_MES = '$newMessage', S_STA_REG = '$statusReg' WHERE S_ID = '$requestId'";
    if ($conn->query($updateSmesQuery) === TRUE) {
        // Handle File Upload
        if (isset($_FILES['fileInput']['name'])) {
            $proof_img_name = $_FILES['fileInput']['name'];
            $proof_tmp_name = $_FILES['fileInput']['tmp_name'];
            $proof_error = $_FILES['fileInput']['error'];

            if ($proof_error === 0) {
                $proof_img_ex = pathinfo($proof_img_name, PATHINFO_EXTENSION);
                $proof_img_ex_to_lc = strtolower($proof_img_ex);
                $allowed_exs = array('jpg', 'jpeg', 'png', 'pdf', 'docx');

                if (in_array($proof_img_ex_to_lc, $allowed_exs)) {
                    $new_proof_img_name = uniqid($requestId, true) . '.' . $proof_img_ex_to_lc;
                    $proof_img_upload_path = '../upload/' . $new_proof_img_name;

                    move_uploaded_file($proof_tmp_name, $proof_img_upload_path);

                    // Update the fileInput field
                    $updateFileInputQuery = "UPDATE request SET fileInput = '$new_proof_img_name' WHERE S_ID = '$requestId'";
                    if ($conn->query($updateFileInputQuery) !== TRUE) {
                        // Handle error if updating fileInput fails
                    header("Location: ../student/pending?success=Updated successfully");
                    exit;
                    }
                }
            }
        }

        header("Location: ../student/pending?success=Updated successfully");
        exit;
    } else {
        header("Location: ../student/pending?error=Not_updated");
        exit;
    }
}
?>