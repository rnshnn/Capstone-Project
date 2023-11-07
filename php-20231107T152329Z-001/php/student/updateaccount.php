<?php

session_start();

$currentTime = time();
$lastUpdateTime = $_SESSION['R_CODE_ISSUE_TIMESTAMP'];

if ($currentTime - $lastUpdateTime < 1000) {

    header("location: ../../student/account?error=You have recently updated. Please wait for several minutes before attempting another update or try to signing in again.");
    exit();
}

include "../dbcon.php";

// Retrieve data from the form
$firstName = $_POST['R_FIRST'];
$middleName = $_POST['R_MIDD'];
$lastName = $_POST['R_FULL'];
$schoolID = $_POST['R_STU'];
$course = $_POST['R_COU'];
$year = $_POST['R_YEAR'];
$contactNumber = $_POST['R_CON'];
$houseUnitNo = $_POST['R_ADD'];
$street = $_POST['R_STRE'];
$district = $_POST['R_BRGY'];
$city = $_POST['R_MUNI'];
$region = $_POST['R_CITY'];
$undergraduateSemesterYear = $_POST['R_UN'];
$position = $_POST['R_POS'];
$company = $_POST['R_COM'];

$sql = "UPDATE register SET
        R_FIRST = '$firstName',
        R_MIDD = '$middleName',
        R_FULL = '$lastName',
        R_STU = '$schoolID',
        R_COU = '$course',
        R_YEAR = '$year',
        R_CON = '$contactNumber',
        R_ADD = '$houseUnitNo',
        R_STRE = '$street',
        R_BRGY = '$district',
        R_MUNI = '$city',
        R_CITY = '$region',
        R_UN = '$undergraduateSemesterYear',
        R_POS = '$position',
        R_COM = '$company'
        WHERE R_ID = " . $_SESSION['R_ID'];

if ($conn->query($sql) === TRUE) {

    $_SESSION['R_CODE_ISSUE_TIMESTAMP'] = $currentTime;
    header("location: ../../student/account?success=Account updated successfully. Please try signing in again.");
    exit();
} else {
    header("location: ../../student/account?error=Error to update");
    exit();
}

$conn->close();

