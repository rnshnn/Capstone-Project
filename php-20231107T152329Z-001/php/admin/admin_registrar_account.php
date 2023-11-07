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
        $R_FULL = $account["R_FULL"];
        $R_STU = $account["R_STU"];
        $R_EMAIL = $account["R_EMAIL"];
        $R_COM = $account["R_COM"];
        $R_POS = $account["R_POS"];
        $R_PASS = $account["R_PASS"];
        $R_VERIFIED = $account["R_VERIFIED"];
        $R_RORA = $account["R_RORA"];
        $R_SMS = $account["R_SMS"];

        // Insert data into the database
        $sql = "INSERT INTO register (R_FULL, R_STU, R_EMAIL, R_COM, R_POS, R_PASS, R_VERIFIED, R_RORA, R_SMS)
                VALUES ('$R_FULL', '$R_STU', '$R_EMAIL', '$R_COM', '$R_POS', '$R_PASS', '$R_VERIFIED','$R_RORA','$R_SMS')";

        if (!mysqli_query($conn, $sql)) {
            // Error occurred during insertion
            header("Location: ../../admin/user?error=Error");
            exit;
        }
    }
    
    // Data inserted successfully
    header("Location: ../../admin/user?success=Add Account successfully");
    exit;
}

mysqli_close($conn);
?>
