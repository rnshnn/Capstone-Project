<?php
session_start();

if (!isset($_SESSION['R_EMAIL'])) {
    session_destroy();
    header("Location: ../signup");
    exit;
}

include "db.php";

if (isset($_POST['verify'])) {
    $R_CODE = mysqli_real_escape_string($conn, $_POST['R_CODE']);
    $R_EMAIL = $_SESSION['R_EMAIL'];

    // Check if the entered code matches the stored code
    $verifyQuery = "SELECT * FROM register WHERE R_EMAIL = ? AND R_CODE = ?";
    $stmt = mysqli_prepare($conn, $verifyQuery);
    mysqli_stmt_bind_param($stmt, "ss", $R_EMAIL, $R_CODE);
    mysqli_stmt_execute($stmt);
    $runVerifyQuery = mysqli_stmt_get_result($stmt);

    if ($runVerifyQuery && mysqli_num_rows($runVerifyQuery) > 0) {
        // Code matched
        // Update the verification status and issue timestamp
        $updateQuery = "UPDATE register SET R_CODE = NULL, R_VERIFIED = 1, R_CODE_ISSUE_TIMESTAMP = NULL WHERE R_EMAIL = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "s", $R_EMAIL);
        $updateResult = mysqli_stmt_execute($stmt);

        if ($updateResult) {
            header("Location: ../signin?success=Your request has been successfully sent. Please wait for an Email notification.");
            exit;
        } else {
            header("Location: verify?error=Failed to update verification status.");
            exit;
        }
    } else {
        header("Location: verify?error=Invalid verification code.");
        exit;
    }
}

$expiryTimeframe = 1000; 

// Delete unverified accounts that have expired
$deleteExpiredQuery = "DELETE FROM register WHERE R_VERIFIED = 0 AND R_CODE_ISSUE_TIMESTAMP IS NOT NULL AND TIMESTAMPDIFF(SECOND, R_CODE_ISSUE_TIMESTAMP, NOW()) > $expiryTimeframe";
$deleteExpiredResult = mysqli_query($conn, $deleteExpiredQuery);

if (mysqli_affected_rows($conn) > 0) {
    header("Location: ../signup?error= Account verification code Expired please try to singing up again.");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/signup.css">
    <title>RMS - Verify</title>
</head>

<body>

    <div class="center-container">
        <form class="custom-form" method="post" action="verify.php">

            <?php if(isset($_GET['error'])){ ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_GET['error']; ?>
            </div>
            <?php } ?>

            <?php if(isset($_GET['success'])){ ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_GET['success']; ?>
            </div>
            <?php } ?>
            <div class="header">
                <img src="../img/favicon - Copy.png" alt="Logo" class="logo">
                <div class="title">REGISTRAR MANAGEMENT SYSTEM</div>
            </div>

            <div class="form-outline">
                <label class="form-label" for="form2Example2">Verification code</label>
                <input type="number" id="form2Example2" name="R_CODE" class="form-control"
                    placeholder="Enter your code" />
            </div>

            <button type="submit" name="verify" class="btn btn-primary btn-block">Verify</button>
        </form>

    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="../js/registerinput.js"></script>



</body>

</html>