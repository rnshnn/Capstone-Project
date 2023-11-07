<?php
include_once("../dbcon.php");

session_start();

if (isset($_POST['forgot_password'])) {
    $R_EMAIL = $_POST['R_EMAIL'];

    // Check if the user has already requested a password reset within the last 10 hours
    if (isset($_SESSION['R_CODE_ISSUE_TIMESTAMP'])) {
        $timeSinceLastRequest = strtotime(date('Y-m-d H:i:s')) - strtotime($_SESSION['R_CODE_ISSUE_TIMESTAMP']);
        if ($timeSinceLastRequest < 36000) { // 36000 seconds = 10 hours
            header("Location: forgot?error=Please wait at least 10 hours before requesting another reset.");
            exit;
        }
    }

    // Validate that the email exists in the database
    $emailCheckQuery = "SELECT R_EMAIL, R_CODE_ISSUE_TIMESTAMP FROM register WHERE R_EMAIL = '$R_EMAIL'";
    $emailCheckResult = mysqli_query($conn, $emailCheckQuery);

    if ($emailCheckResult && mysqli_num_rows($emailCheckResult) > 0) {
        $userRow = mysqli_fetch_assoc($emailCheckResult);

        // Check if the user has not requested a reset before or it's been more than 10 hours
        if (!isset($userRow['R_CODE_ISSUE_TIMESTAMP']) || strtotime(date('Y-m-d H:i:s')) - strtotime($userRow['R_CODE_ISSUE_TIMESTAMP']) >= 36000) {
            $R_CODE = rand(999999, 111111);
            $updateQuery = "UPDATE register SET R_CODE = $R_CODE, R_CODE_ISSUE_TIMESTAMP = NOW() WHERE R_EMAIL = '$R_EMAIL'";
            $updateResult = mysqli_query($conn, $updateQuery);
            if ($updateResult) {
                $subject = 'Email Verification Code';
                $message = "Your verification code is $R_CODE";
                $sender = 'From: ma382793@gmail.com';

                if (mail($R_EMAIL, $subject, $message, $sender)) {
                    $_SESSION['R_EMAIL'] = $R_EMAIL;
                    header("Location: verifyEmail.php?success=Sent a verification code to your Email $R_EMAIL");
                    exit;
                } else {
                    header("Location: forgot?error=Failed while sending code!");
                    exit;
                }
            } else {
                header("Location: forgot?error=Failed while updating data in the database!");
                exit;
            }
        } else {
            header("Location: forgot?error=Please wait at least 10 hours before requesting another reset.");
            exit;
        }
    } else {
        header("Location: forgot?error=Invalid Email Address");
        exit;
    }
}

if (isset($_POST['verify'])) {
    $R_EMAIL = $_SESSION['R_EMAIL'];
    $_SESSION['message'] = "";
    $R_CODE = mysqli_real_escape_string($conn, $_POST['R_CODE']);
    $verifyQuery = "SELECT * FROM register WHERE R_EMAIL = '$R_EMAIL'";
    $runVerifyQuery = mysqli_query($conn, $verifyQuery);
    if ($runVerifyQuery) {
        if (mysqli_num_rows($runVerifyQuery) > 0) {
            $row = mysqli_fetch_assoc($runVerifyQuery);
            if ($row['R_CODE'] === $R_CODE) {
                $newQuery = "UPDATE register SET R_CODE ='none' WHERE R_EMAIL = '$R_EMAIL'";
                $run = mysqli_query($conn, $newQuery);
                header("Location: newPassword.php");
                exit;
            } else {
                header("Location: verifyEmail?error=Invalid Verification Code.");
                exit;
            }
        } else {
            header("Location: verifyEmail?error=Invalid Verification Code.");
            exit;
        }
    } else {
        header("Location: verifyEmail?error=Failed while checking Verification Code from database!");
        exit;
    }
}

if (isset($_POST['changePassword'])) {
    $R_EMAIL = $_SESSION['R_EMAIL'];
    $R_PASS = ($_POST['R_PASS']);
    $R_CPASS = ($_POST['R_CPASS']);

    if (strlen($_POST['R_PASS']) < 8) {
        header("Location: newPassword.php?error=Use 8 or more characters with a mix of letters, numbers & symbols");
        exit;
    } else {
        // if password not matched
        if ($_POST['R_PASS'] != $_POST['R_CPASS']) {
            header("Location: newPassword.php?error=Password not matched");
            exit;
        } else {
            $updatePassword = "UPDATE register SET R_PASS = '$R_PASS' WHERE R_EMAIL = '$R_EMAIL'";
            $updatePass = mysqli_query($conn, $updatePassword) or die("Query Failed");
            session_destroy();
            header("Location: ../../signin?success=Your password has been changed");
            exit;
        }
    }
}
?>