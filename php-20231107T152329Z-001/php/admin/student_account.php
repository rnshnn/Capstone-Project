<?php
$conn = mysqli_connect("localhost", "root", "", "rms");

if (!$conn) {
    die('Connection Failed' . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $accounts = $_POST["accounts"];
    
    // Loop through the array of accounts and insert them one by one
    foreach ($accounts as $account) {
        $R_FIRST = $account["R_FIRST"];
        $R_STU = $account["R_STU"];
        $R_EMAIL = $account["R_EMAIL"];
        $R_PASS = $account["R_PASS"];
        $R_COU = $account["R_COU"];
        $R_YEAR = $account["R_YEAR"];
        $R_VERIFIED = $account["R_VERIFIED"];
        $R_RORA = $account["R_RORA"];
        $R_SMS = $account["R_SMS"];
        $R_STU_POS = $account["R_STU_POS"];
        $R_STATUS = $account["R_STATUS"];

        // Insert data into the database
        $sql = "INSERT INTO register (R_FIRST, R_STU, R_EMAIL, R_PASS, R_COU, R_YEAR, R_VERIFIED, R_RORA, R_SMS, R_STU_POS, R_STATUS)
                VALUES ('$R_FIRST', '$R_STU', '$R_EMAIL', '$R_PASS', '$R_COU', '$R_YEAR', '$R_VERIFIED','$R_RORA','$R_SMS','$R_STU_POS','$R_STATUS')";

        if (!mysqli_query($conn, $sql)) {
            // Error occurred during insertion
            header("Location: ../../admin/student?error=Error");
            exit;
        }
    }
    
    // Data inserted successfully
    header("Location: ../../admin/student?success=Add Account successfully");
    exit;
}

mysqli_close($conn);
?>
