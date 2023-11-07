<?php
session_start();

// Update the status to "Not Active" in the database
if (isset($_SESSION['R_EMAIL'])) {
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database_name = "rms";

    $conn = mysqli_connect($server_name, $username, $password, $database_name);

    if (!$conn) {
        die("Connection Failed: " . mysqli_connect_error());
    }

    $R_EMAIL = $_SESSION['R_EMAIL'];

    $updateStatusSQL = "UPDATE register SET R_STATUS = 'Not Active' WHERE R_EMAIL = '$R_EMAIL'";
    mysqli_query($conn, $updateStatusSQL);

    mysqli_close($conn);
}

// Unset and destroy the session
session_unset();
session_destroy();

header("location: ../signin");
?>