<?php
include "../php/dbcon.php";
session_start();
if (isset($_SESSION['R_VERIFIED']) && isset($_SESSION['R_FULL']) && isset($_SESSION['R_EMAIL']) && isset($_SESSION['R_FIRST']) && isset($_SESSION['R_MIDD'])) {

    $expiryTimeframe = 440000;
    $allowedS_MES = ['send proof of payment'];
    $newMessage = "Request declined due to several days of no response to payment."; 

    $selectQuery = "SELECT * FROM payment WHERE S_MES IN (?) AND P_STAMP IS NOT NULL AND TIMESTAMPDIFF(SECOND, P_STAMP, NOW()) > ?";
    $stmtSelect = mysqli_prepare($conn, $selectQuery);
    mysqli_stmt_bind_param($stmtSelect, "si", $allowedS_MES[0], $expiryTimeframe);
    mysqli_stmt_execute($stmtSelect);
    $result = mysqli_stmt_get_result($stmtSelect);

    $row = mysqli_fetch_assoc($result);

    if ($row) {

        $insertQuery = "INSERT INTO decline (C_ID, C_FULL, C_COU, C_YEAR, C_SID, C_documentType, C_numCopies, C_documentType_2, C_numCopies_2, C_documentType_3, C_numCopies_3, C_firstRequest, C_price, C_CODE, S_MES, C_ASIG, C_DATE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = mysqli_prepare($conn, $insertQuery);
        if ($stmtInsert) {
            mysqli_stmt_bind_param($stmtInsert, "issssssssssssssss", $row['P_ID'], $row['P_FULL'], $row['P_COU'], $row['P_YEAR'], $row['P_SID'], $row['P_documentType'], $row['P_numCopies'], $row['P_documentType_2'], $row['P_numCopies_2'], $row['P_documentType_3'], $row['P_numCopies_3'], $row['P_firstRequest'], $row['P_price'], $row['P_CODE'], $newMessage, $row['P_ASIG'], $row['P_DATE']);
            $insertResult = mysqli_stmt_execute($stmtInsert);

            if ($insertResult) {

                $deleteQuery = "DELETE FROM payment WHERE S_MES IN (?) AND P_STAMP IS NOT NULL AND TIMESTAMPDIFF(SECOND, P_STAMP, NOW()) > ?";
                $stmtDelete = mysqli_prepare($conn, $deleteQuery);
                mysqli_stmt_bind_param($stmtDelete, "si", $allowedS_MES[0], $expiryTimeframe);
                $deleteResult = mysqli_stmt_execute($stmtDelete);

                if ($deleteResult) {
                    header('Location: payment?error=Request declined due to several days of no response to your payment.');
                    exit();
                } else {
                    header('Location: payment?error=Deletion error after approval');
                    exit();
                }
            } else {
                header('Location: payment?error=Payment insertion error');
                exit();
            }
        }
    }
?>


<?php
if (!isset($_SESSION['R_VERIFIED']) || $_SESSION['R_VERIFIED'] != "1") {
    header("location: ../signin");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://js.paymongo.com/v2/paymongo.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dasboard.css">
    <link rel="stylesheet" href="../css/nitification.css">

    <title>RMS - Student payment</title>
</head>

<body style="display: flex; flex-direction: column; min-height: 100vh;">
    <header>
    </header>

    <div style="flex: 1; display: flex;">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light border-right" id="sidebar">
            <div class="sidebar-heading p-3">
                <img src="../img/Phinma-logi.jpg" alt="Logo" class="img-fluid">
                <h5 style="font-weight: 700;">REGISTRAR MANAGEMENT SYSTEM</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="Dashboard" class="list-group-item list-group-item-action ">
                    <i class="fas fa-book mr-2"></i><span class="sidebar-text">Dashboard</span>
                </a>
                <a href="Request" class="list-group-item list-group-item-action">
                    <i class="fas fa-tasks mr-2"></i><span class="sidebar-text">Request List</span>
                </a>
                <a href="pending" class="list-group-item list-group-item-action">
                    <i class="fas fa-hourglass-half mr-2"></i><span class="sidebar-text">Pending Request</span>
                </a>
                <a href="Payment/<?= urlencode($registrar_name) ?>"
                    class="list-group-item list-group-item-action active">
                    <i class="fas fa-money-bill-wave mr-2"></i><span class="sidebar-text">Payment</span>
                </a>
                <a href="Onprocess" class="list-group-item list-group-item-action">
                    <i class="fas fa-cogs mr-2"></i><span class="sidebar-text">On Process Request</span>
                </a>
                <a href="Releasing" class="list-group-item list-group-item-action">
                    <i class="fas fa-hourglass-start mr-2"></i><span class="sidebar-text">Releasing Request</span>
                </a>
                <a href="done" class="list-group-item list-group-item-action">
                    <i class="fas fa-check-circle mr-2"></i><span class="sidebar-text">Done Request</span>
                </a>
                <a href="Account" class="list-group-item list-group-item-action">
                    <i class="fas fa-user-circle mr-2"></i><span class="sidebar-text">Account</span>
                </a>
                <a href="../php/signout" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt mr-2"></i><span class="sidebar-text">Logout</span>
                </a>
            </div>
        </nav>

        </nav>

        <main role="main" class="col-md-9 ml-md-auto col-lg-10 px-1">
        <button id="sidebarToggle" class="btn btn-secondary btn-notification position-fixed"
                style="top: 15px; font-size: 12px"> <i class="fas fa-bars mr2"></i></button>
            <div class="container-fluid">
                <div class="mt-5">
                    <h3 class="mb-4"> <i class="fas fa-money-bill-wave mr-2"></i> Payment</h3>

                    <?php
                $registrar_email = $_SESSION['R_EMAIL'];
            
                require '../php/dbcon.php';
            
                $query = "SELECT * FROM payment WHERE P_EMAIL = '$registrar_email'";
                $query_run = mysqli_query($conn, $query);
            
                $row = mysqli_num_rows($query_run);
            
                echo '<h6>You have (' . $row . ') Payment request</h6>';
                ?>

                    <div class="input-group mb-3">
                        <form action="" method="GET" class="w-50 d-flex align-items-center">
                            <input type="text" name="search" class="form-control flex-grow-1"
                                placeholder="Search Name, Request and Reference No." aria-label="Search input"
                                aria-describedby="searchButton">
                            <button class="btn btn-primary" type="submit" id="searchButton"><i
                                    class="fas fa-search"></i></button>
                        </form>
                    </div>


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


                    <table class="table table-bordered table-striped table-hover table table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Request</th>
                                <th>Copy</th>
                                <th>Price</th>
                                <th>Reference No.</th>
                                <th>Gcash</th>
                                <th>Message</th>
                                <th>Deliver/Pick up</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        require '../php/dbcon.php';
                        $registrar_name = $_SESSION['R_EMAIL'];

                        // Define number of results per page
                        $results_per_page = 50;

                        // Get current page
                        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                            $current_page = intval($_GET['page']);
                        } else {
                            $current_page = 1;
                        }

                        // Calculate offset for LIMIT clause
                        $offset = ($current_page - 1) * $results_per_page;

                        // Get total number of results
                        if (isset($_GET['search'])) {
                            $filtervalues = '%' . $_GET['search'] . '%'; // Add % wildcards
                            $query = "SELECT COUNT(*) as total FROM payment WHERE P_EMAIL=? AND CONCAT(P_FULL,P_CODE,P_documentType) LIKE ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ss", $registrar_name, $filtervalues);
                        } else {
                            $query = "SELECT COUNT(*) as total FROM payment WHERE P_EMAIL=?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("s", $registrar_name);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $total_results = $result->fetch_assoc()['total'];

                        // Calculate total number of pages
                        $total_pages = ceil($total_results / $results_per_page);

                        // Modify database query to include LIMIT clause
                        if (isset($_GET['search'])) {
                            $filtervalues = '%' . $_GET['search'] . '%'; // Add % wildcards
                            $query = "SELECT * FROM payment WHERE P_EMAIL=? AND CONCAT(P_FULL,P_CODE,P_documentType) LIKE ? LIMIT $results_per_page OFFSET $offset";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ss", $registrar_name, $filtervalues);
                        } else {
                            $query = "SELECT * FROM payment WHERE P_EMAIL=? LIMIT $results_per_page OFFSET $offset";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("s", $registrar_name);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $i = 1;
                            foreach ($result as $users) {
                        ?>
                            <tr data-toggle="modal" data-target="#recordModal<?= $i; ?>">
                                <td><?= $i++ ?></td>
                                <td><?= $users['P_FULL'] ?></td>
                                <td><?= $users['P_COU'] ?></td>
                                <td><?= $users['P_YEAR'] ?></td>
                                <td><?= $users['P_documentType'] ?> <br> <?= $users['P_documentType_2'] ?> <br> <?= $users['P_documentType_3'] ?></td>
                                <td><?= $users['P_numCopies'] ?> <br> <?= $users['P_numCopies_2'] ?> <br> <?= $users['P_numCopies_3'] ?></td>
                                <td><?= $users['P_price'] ?></td>
                                <td><?= $users['P_CODE'] ?></td>
                                <td><a href="https://m.gcash.com/gcash-login-web/index.html#/" target="_blank">Gcash</a>
                                </td>
                                <td id="S_MES_<?= $i; ?>"><?= $users['S_MES']; ?></td>
                                <td><?= $users['P_DEL'] ?></td>
                                <td><?= $users['P_DATE'] ?></td>
                            </tr>
                            <script>
                            const S_MES_<?= $i; ?> = document.getElementById("S_MES_<?= $i; ?>");
                            const messageContent_<?= $i; ?> = S_MES_<?= $i; ?>.textContent.trim();

                            if (messageContent_<?= $i; ?>.toLowerCase() === 'already update' ||
                                messageContent_<?= $i; ?>.toLowerCase() === 'fully paid' ||
                                messageContent_<?= $i; ?>.toLowerCase() === 'send proof of payment') {
                                S_MES_<?= $i; ?>.style.color = "green";
                            } else {
                                S_MES_<?= $i; ?>.style.color = "orangered";
                            }
                            </script>
                            <?php
                                                }
                                            }
                                            else
                                            {
                                                echo "<h5> No Record Found </h5>";
                                            }
                                        ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="13">
                                    <div class="d-flex justify-content-center">
                                        <ul class="pagination">
                                            <?php if ($current_page > 1) : ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="?page=<?= $current_page - 1 ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>"
                                                    tabindex="-1" aria-disabled="true">
                                                    &lt;&lt;
                                                </a>
                                            </li>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                            <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                                <a class="page-link"
                                                    href="?page=<?= $i ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                            <?php endfor; ?>

                                            <?php if ($current_page < $total_pages) : ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="?page=<?= $current_page + 1 ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                                                    &gt;
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </main>

        <?php
    if ($result->num_rows > 0) {
        $i = 1;
        foreach ($result as $users) {
    ?>

        <div class="modal fade" id="recordModal<?= $i; ?>" tabindex="-1" role="dialog"
            aria-labelledby="recordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="recordModalLabel">Student Record Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <?= $users['P_FULL']; ?></p>
                                <p><strong>Student ID:</strong> <?= $users['P_SID']; ?></p>
                                <p><strong>Course:</strong> <?= $users['P_COU']; ?></p>
                                <p><strong>Year:</strong> <?= $users['P_YEAR']; ?></p>
                                <p><strong>If under Graduation:</strong> <?= $users['P_UND']; ?></p>
                                <p><strong>Company:</strong> <?= $users['P_COM']; ?></p>
                                <p><strong>Position:</strong> <?= $users['P_POS']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Request (1):</strong> <?= $users['P_documentType']; ?> -  <?= $users['P_numCopies']; ?></p>
                                <p><strong>Request (2):</strong> <?= $users['P_documentType_2']; ?> -  <?= $users['P_numCopies_2']; ?></p>
                                <p><strong>Request (3):</strong> <?= $users['P_documentType_3']; ?> -  <?= $users['P_numCopies_3']; ?></p>
                                <p><strong>First request:</strong> <?= $users['P_firstRequest']; ?></p>
                                <p><strong>Price:</strong> <?= $users['P_price']; ?></p>
                                <p><strong>Reference No:</strong> <?= $users['P_CODE']; ?></p>
                                <p><strong>Date of request:</strong> <?= $users['P_DATE']; ?></p>
                            </div>
                        </div>

                        <!-- Approve and Return Forms -->
                        <div class="mt-4">
                            <p><strong>Proof of payment:</strong></p>
                            <form action="../php/paymentstudent" method="post" class="mt-2" id="returnForm<?= $i; ?>"
                                enctype="multipart/form-data">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="P_PROOF<?= $i; ?>"
                                            name="P_PROOF">
                                        <label class="custom-file-label" for="P_PROOF<?= $i; ?>">Chooce file..</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-info btn-sm" type="button"
                                            id="submitRequestButton<?= $i; ?>"
                                            <?= strtolower($users['S_MES']) === 'already update' || $users['S_MES'] === 'fully paid' ? 'disabled' : '' ?>>Submit</button>
                                    </div>
                                </div>
                                <input type="hidden" name="request_id" value="<?= $users['P_ID']; ?>">
                            </form>


                            <script>
                            document.getElementById('submitRequestButton<?= $i; ?>').addEventListener('click', function(
                                event) {
                                event.preventDefault(); // Prevent the default form submission

                                const P_PROOF = document.getElementById('P_PROOF<?= $i; ?>');
                                if (!P_PROOF.files.length) {
                                    Swal.fire({
                                        title: 'File Required',
                                        text: 'Please select a file before submitting.',
                                        icon: 'error',
                                    });
                                    return; // Exit the function if file is not selected
                                }

                                Swal.fire({
                                    title: 'Submit',
                                    text: 'Are you sure you want to submit this file?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, Submit it!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Continue with the form submission
                                        document.getElementById('returnForm<?= $i; ?>').submit();
                                    }
                                });
                            });
                            </script>

                            <script>
                            document.getElementById('P_PROOF<?= $i; ?>').addEventListener('change', function(event) {
                                const fileName = event.target.files[0].name;
                                const label = document.querySelector(
                                    '.custom-file-label[for="P_PROOF<?= $i; ?>"]');
                                label.textContent = fileName;
                            });
                            </script>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php
            $i++;
        }
    }
    ?>

    </div>

    <button class="btn btn-secondary btn-notification position-fixed" style="top: 15px; right: 40px; font-size:12px">
        <i class="fas fa-user-circle mr-2"></i>
        <?php echo htmlspecialchars($_SESSION['R_FIRST'] . ' ' . $_SESSION['R_MIDD'] . ' ' . $_SESSION['R_FULL']); ?>
    </button>

    <footer class="mt-auto" style="background-color: #f2f2f2; text-align: center; padding: 2px 0; font-size: 10px;">
        <p>&copy; 2023 Registrar Management System. All rights reserved.</p>
    </footer>

    <?php include "../php/student/studentjs.php"; ?>
    <?php include "../php/student/notification.php"; ?>
    
</body>

</html>



<?php

} else {
    header("location: ../signin");
    exit();
}
?>