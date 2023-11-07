<?php
session_start();
$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "rms";

$conn = mysqli_connect($server_name, $username, $password, $database_name);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

if (isset($_POST['R_EMAIL']) && isset($_POST['R_PASS'])) {

    // Define the maximum number of allowed attempts and the lockout time in seconds
    $max_attempts = 7;
    $lockout_time = 31; // seconds

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $R_EMAIL = validate($_POST['R_EMAIL']);
    $R_PASS = validate($_POST['R_PASS']);

    if (empty($R_EMAIL)) {
        header("location: ../signin?error=Email Required");
        exit();
    } elseif (empty($R_PASS)) {
        header("location: ../signin?error=Password Required");
        exit();
    } else {
        // Check if the user is locked out due to too many incorrect attempts
        $current_time = time();
        $last_attempt_time = isset($_SESSION['last_attempt_time']) ? $_SESSION['last_attempt_time'] : 0;
        $attempts = isset($_SESSION['attempts']) ? $_SESSION['attempts'] : 0;

        if ($attempts >= $max_attempts && $current_time - $last_attempt_time < $lockout_time) {
            $remaining_lockout_time = $lockout_time - ($current_time - $last_attempt_time);
            header("location: ../signin?error=Too many incorrect attempts. Try again in $remaining_lockout_time seconds.");
            exit();
        }

        // Attempt to authenticate the user and retrieve their role and verification status
        $sql = "SELECT * FROM register WHERE R_EMAIL='$R_EMAIL' AND R_PASS='$R_PASS'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            // Check if the account is approved or rejected
            $R_RORA = $row['R_RORA'];
            if ($R_RORA === 'approve') {
                // Set session variables based on user's role
                $R_VERIFIED = $row['R_VERIFIED'];
                $R_FULL = $row['R_FULL'];
                $R_FIRST = $row['R_FIRST'];
                $R_MIDD = $row['R_MIDD'];
                $R_EMAIL = $row['R_EMAIL'];
                $R_STU = $row['R_STU'];
                $R_COU = $row['R_COU'];
                $R_YEAR = $row['R_YEAR'];
                $R_UN = $row['R_UN'];
                $R_POS = $row['R_POS'];
                $R_COM = $row['R_COM'];
                $R_CON = $row['R_CON'];
                $R_ADD = $row['R_ADD'];
                $R_STRE = $row['R_STRE'];
                $R_BRGY = $row['R_BRGY'];
                $R_MUNI = $row['R_MUNI'];
                $R_CITY = $row['R_CITY'];
                $R_ID  = $row['R_ID'];
                $R_CODE_ISSUE_TIMESTAMP = $row['R_CODE_ISSUE_TIMESTAMP'];

                $_SESSION['R_ID'] = $R_ID;
                $_SESSION['R_CODE_ISSUE_TIMESTAMP'] = $R_CODE_ISSUE_TIMESTAMP;
                $_SESSION['R_STU'] = $R_STU;
                $_SESSION['R_COU'] = $R_COU;
                $_SESSION['R_YEAR'] = $R_YEAR;
                $_SESSION['R_UN'] = $R_UN;
                $_SESSION['R_POS'] = $R_POS;
                $_SESSION['R_MUNI'] = $R_MUNI;
                $_SESSION['R_CITY'] = $R_CITY;
                $_SESSION['R_COM'] = $R_COM;
                $_SESSION['R_CON'] = $R_CON;
                $_SESSION['R_ADD'] = $R_ADD;
                $_SESSION['R_STRE'] = $R_STRE;
                $_SESSION['R_BRGY'] = $R_BRGY;
                $_SESSION['R_EMAIL'] = $R_EMAIL;
                $_SESSION['R_FULL'] = $R_FULL;
                $_SESSION['R_FIRST'] = $R_FIRST;
                $_SESSION['R_MIDD'] = $R_MIDD;
                $_SESSION['R_VERIFIED'] = $R_VERIFIED;

                $status = "Active";

                $updateStatusSQL = "UPDATE register SET R_STATUS = 'Active' WHERE R_EMAIL = '$R_EMAIL'";
                mysqli_query($conn, $updateStatusSQL);

                switch ($R_VERIFIED) {
                    case "6":
                        header("location: ../disabled");
                        break;
                    case "5":
                        header("location: ../disable");
                        break;
                    case "4":
                        header("location: ../admin/dashboard");
                        break;
                    case "3":
                        header("location: ../registrar/dashboard");
                        break;
                    case "2":
                        header("location: ../accounting/dashboard");
                        break;
                    case "1":
                        header("location: ../student/dashboard");
                        break;
                    default:
                        header("location: ../signin?error=Invalid Role");
                }
                exit();
            } else {
                header("location: ../signin?error=Account Not Approved");
                exit();
            }
        } else {
            // Incorrect password, update attempts and timestamp
            $_SESSION['attempts'] = $attempts + 5;
            $_SESSION['last_attempt_time'] = $current_time;

            $_SESSION['login_email'] = $R_EMAIL;
            header("location: ../signin?error=Incorrect Email or Password");
            exit();
        }
    }
} else {
    header("location: ../signin");
    exit();
}
?>