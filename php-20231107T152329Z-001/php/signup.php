<?php
if (
    isset($_POST['R_FULL']) &&
    isset($_POST['R_FIRST']) &&
    isset($_POST['R_MIDD']) &&
    isset($_POST['R_STU']) &&
    isset($_POST['R_COU']) &&
    isset($_POST['R_YEAR']) &&
    isset($_POST['R_EMAIL']) &&
    isset($_POST['R_PASS']) &&
    isset($_POST['R_UN']) &&
    isset($_POST['R_POS']) &&
    isset($_POST['R_COM']) &&
    isset($_POST['R_CON']) &&
    isset($_POST['R_ADD']) &&
    isset($_POST['R_STRE']) &&
    isset($_POST['R_BRGY']) &&
    isset($_POST['R_MUNI']) &&
    isset($_POST['R_CITY'])
) {

    session_start();
    include "db.php";

    $R_FULL = $_POST['R_FULL'];
    $R_FIRST = $_POST['R_FIRST'];
    $R_MIDD = $_POST['R_MIDD'];
    $R_STU = $_POST['R_STU'];
    $R_COU = $_POST['R_COU'];
    $R_YEAR = $_POST['R_YEAR'];
    $R_EMAIL = $_POST['R_EMAIL'];
    $R_PASS = $_POST['R_PASS'];
    $R_UN = $_POST['R_UN'];
    $R_POS = $_POST['R_POS'];
    $R_COM = $_POST['R_COM'];
    $R_CON = $_POST['R_CON'];
    $R_ADD = $_POST['R_ADD'];
    $R_STRE = $_POST['R_STRE'];
    $R_BRGY = $_POST['R_BRGY'];
    $R_MUNI = $_POST['R_MUNI'];
    $R_CITY = $_POST['R_CITY'];

    if (isset($_FILES['R_IMG']['name'])) {
        $proof_img_name = $_FILES['R_IMG']['name'];
        $proof_tmp_name = $_FILES['R_IMG']['tmp_name'];
        $proof_error = $_FILES['R_IMG']['error'];

        if ($proof_error === 0) {
            $proof_img_ex = pathinfo($proof_img_name, PATHINFO_EXTENSION);
            $proof_img_ex_to_lc = strtolower($proof_img_ex);

            $allowed_exs = array('jpg', 'jpeg', 'png', 'pdf', 'docx');

            if (in_array($proof_img_ex_to_lc, $allowed_exs)) {
                $new_proof_img_name = uniqid($R_FULL, true) . '.' . $proof_img_ex_to_lc;
                $proof_img_upload_path = '../upload/' . $new_proof_img_name;

                move_uploaded_file($proof_tmp_name, $proof_img_upload_path);
                
                

                
                $R_EMAIL = $_SESSION['R_EMAIL'];
                $checkUser = "SELECT * FROM register WHERE R_EMAIL = ?";
                $stmt = $conn->prepare($checkUser);
                $stmt->bind_param("s", $R_EMAIL);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->num_rows;

                if ($count > 0) {
                    $user = $result->fetch_assoc();
                    if ($user['R_VERIFIED'] == 0) {
                        header("Location: verify?success=Verification code has already been sent to your Email You have several minutes before the code expired");
                        exit;
                    }
                }




                
                $checkUser = "SELECT * FROM register WHERE R_EMAIL=?";
                $stmt = $conn->prepare($checkUser);
                $stmt->bind_param("s", $R_EMAIL);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->num_rows;

                if ($count > 0) {
                    header("Location: ../signin?error=Your account has already been used. If your account is not approved, please wait for an email notification.n");
                    exit;
                } else {
                    // Inserting data into the database
                    $_SESSION['R_EMAIL'] = $R_EMAIL;
                    $R_VERIFIED = 0; // Set R_VERIFIED to 0 initially
                    $sql = "INSERT INTO register (R_FULL, R_FIRST, R_MIDD, R_STU, R_COU, R_YEAR, R_EMAIL, R_PASS, R_UN, R_POS, R_COM, R_CON, R_ADD, R_STRE, R_BRGY, R_MUNI, R_CITY, R_IMG, R_VERIFIED) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssssssssssssssss", $R_FULL, $R_FIRST, $R_MIDD, $R_STU, $R_COU, $R_YEAR, $R_EMAIL, $R_PASS, $R_UN, $R_POS, $R_COM, $R_CON, $R_ADD, $R_STRE, $R_BRGY, $R_MUNI, $R_CITY, $new_proof_img_name, $R_VERIFIED);
                    $stmt->execute();
                    
                    // Sending email notification
                    $R_CODE = rand(11111, 99999);

                    $updateQuery = "UPDATE register SET R_CODE = ? WHERE R_EMAIL = ?";
                    $stmt = mysqli_prepare($conn, $updateQuery);
                    mysqli_stmt_bind_param($stmt, "ss", $R_CODE, $R_EMAIL);
                    $updateResult = mysqli_stmt_execute($stmt);

                    if ($updateResult) {
                        $to = $R_EMAIL;
                        $subject = 'Verification of Document Release Request';
                        $message = "Magandang Araw! Your verification code is: $R_CODE";
                        $headers = "From: fake@example.com";

                        if (mail($to, $subject, $message, $headers)) {
                            header("Location: verify?success=Verification code has been sent to your Email you have several minutes before the code expired");
                            exit;
                        } else {
                            header("Location: ../signup?error=Failed to send email. Please contact support.");
                            exit;
                        }
                    } else {
                        header("Location: ../signup?error=Failed to update verification code.");
                        exit;
                    }
                }
            } else {
                header("Location: ../signup?error=You can't upload files of this type");
                exit;
            }
        } else {
            header("Location: ../signup?error=Proof of Payment & Requirements Required");
            exit;
        }
    } else {
        header("Location: ../signup?error=Proof of Payment & Requirements Required");
        exit;
    }
} else {
    header("Location: ../signup?error=Provide all required information");
    exit;
}
?>
