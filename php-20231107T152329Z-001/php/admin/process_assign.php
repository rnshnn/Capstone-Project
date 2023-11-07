<?php
session_start();
require_once '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedO_ASIG = $_POST['O_ASIG'];

    if (!empty($selectedO_ASIG)) {

        $query = "SELECT R_FULL FROM register WHERE R_VERIFIED = 3 AND R_FULL != ? ORDER BY RAND()";
        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {

            echo "Error preparing the SQL statement: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "s", $selectedO_ASIG);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $assignedName);

                $assignedNames = array();

                while (mysqli_stmt_fetch($stmt)) {

                    $assignedNames[] = $assignedName;
                }

                $updateQuery = "UPDATE process SET O_ASIG = ? WHERE O_ASIG = ? ORDER BY RAND() LIMIT 1";
                $stmt2 = mysqli_prepare($conn, $updateQuery);

                if (!$stmt2) {

                    echo "Error preparing the update SQL statement: " . mysqli_error($conn);
                } else {
                    foreach ($assignedNames as $assignedName) {
                        mysqli_stmt_bind_param($stmt2, "ss", $assignedName, $selectedO_ASIG);

                        if (mysqli_stmt_execute($stmt2)) {
                            header('Location: ../../admin/onprocess?success=Successfully assigned');
                 
                        } else {
                            header('Location: ../../admin/onprocess?error=Failed to update');
                        }
                    }
                }
            } else {
                header('Location: ../../admin/onprocess?error=Error executing the query');
            }
        }
    } else {
        header('Location: ../../admin/onprocess?error=Please select a valid name.');
    }
}
?>
