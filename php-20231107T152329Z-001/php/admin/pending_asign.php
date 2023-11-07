<?php
session_start();
require_once '../dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedS_ASIG = $_POST['S_ASIG'];

    if (!empty($selectedS_ASIG)) {

        $query = "SELECT R_FULL FROM register WHERE R_VERIFIED = 3 AND R_FULL != ? ORDER BY RAND()";
        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {

            echo "Error preparing the SQL statement: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "s", $selectedS_ASIG);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $assignedName);

                $assignedNames = array();

                while (mysqli_stmt_fetch($stmt)) {

                    $assignedNames[] = $assignedName;
                }

                $updateQuery = "UPDATE request SET S_ASIG = ? WHERE S_ASIG = ? ORDER BY RAND() LIMIT 1";
                $stmt2 = mysqli_prepare($conn, $updateQuery);

                if (!$stmt2) {

                    echo "Error preparing the update SQL statement: " . mysqli_error($conn);
                } else {
                    foreach ($assignedNames as $assignedName) {
                        mysqli_stmt_bind_param($stmt2, "ss", $assignedName, $selectedS_ASIG);

                        if (mysqli_stmt_execute($stmt2)) {
                            header('Location: ../../admin/pending?success=Successfully assigned');
                 
                        } else {
                            header('Location: ../../admin/pending?error=Failed to update');
                        }
                    }
                }
            } else {
                header('Location: ../../admin/pending?error=Error executing the query');
            }
        }
    } else {
        header('Location: ../../admin/pending?error=Please select a valid name.');
    }
}
?>
