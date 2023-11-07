<?php
require '../php/dbcon.php'; 
session_start();
if (isset($_SESSION['R_VERIFIED']) && isset($_SESSION['R_FULL']) && isset($_SESSION['R_EMAIL']) && isset($_SESSION['R_FIRST']) && isset($_SESSION['R_MIDD'])) {

?>
<?php
if (!isset($_SESSION['R_VERIFIED']) || $_SESSION['R_VERIFIED'] != "4") {
    header("location: ../signin");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dasboard.css">
    <link rel="stylesheet" href="../css/nitification.css">

    <title>RMS - Admin payment</title>
</head>

<body style="display: flex; flex-direction: column; min-height: 100vh;">
    <header>
        <!-- Header content goes here -->
    </header>

    <div style="flex: 1; display: flex;">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light border-right" id="sidebar">
            <div class="sidebar-heading p-3">
                <img src="../img/Phinma-logi.jpg" alt="Logo" class="img-fluid">
                <h5 style="font-weight: 700;" class="mb-0">REGISTRAR MANAGEMENT SYSTEM</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="Dashboard" class="list-group-item list-group-item-action">
                    <i class="fas fa-book mr-2"></i> Dashboard
                </a>
                <a href="user" class="list-group-item list-group-item-action">
                    <i class="fas fa-users mr-2"></i> User
                    Account&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
                        class="fas fa-caret-down "></i>
                </a>
                <a href="pending" class="list-group-item list-group-item-action active">
                    <i class="fas fa-clock mr-2"></i> Pending Request
                </a>
                <a href="Payment" class="list-group-item list-group-item-action">
                    <i class="fas fa-money-bill-wave mr-2"></i> Payment
                </a>
                <a href="Onprocess" class="list-group-item list-group-item-action">
                    <i class="fas fa-cogs mr-2"></i> On Process Request
                </a>
                <a href="Releasing" class="list-group-item list-group-item-action">
                    <i class="fas fa-hourglass-start mr-2"></i> Releasing Request
                </a>
                <a href="done" class="list-group-item list-group-item-action ">
                    <i class="fas fa-check-circle mr-2"></i> Done Request
                </a>
                <a href="decline" class="list-group-item list-group-item-action ">
                    <i class="fas fa-times-circle mr-2"></i> Decline Request
                </a>
                <a href="../php/signout" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>


        </nav>

        <main role="main" class="col-md-9 ml-md-auto col-lg-10 px-1">
            <div class="container-fluid">
                <div class="mt-5">
                    <h3 class="mb-4"> <i class="fas fa-clock mr-2"></i> Pending Request</h3>

                    <?php

                    $query = "SELECT COUNT(*) AS total FROM request"; // Count all rows in the table
                    $query_run = mysqli_query($conn, $query);
                    
                    if ($query_run) {
                        $row = mysqli_fetch_assoc($query_run);
                        $row_count = $row['total'];
                        
                        echo '<h6>You have (' . $row_count . ') Pending request</h6>';
                    } else {
                        echo "Query failed: " . mysqli_error($conn);
                    }
                ?>

                    <div class="input-group mb-3">
                        <form action="" method="GET" class="w-50 d-flex align-items-center">

                            <select type="text" name="search" class="form-control flex-grow-1 mr-1"
                                placeholder="Search registrar name..." aria-label="Search input"
                                aria-describedby="searchButton">
                                <option hidden>Select Search..</option>
                                <?php
                                    $query = "SELECT DISTINCT S_ASIG FROM request";
                                    $result = mysqli_query($conn, $query);

                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $S_ASIG = $row['S_ASIG'];
                                            echo "<option value='$S_ASIG'>$S_ASIG</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options available</option>";
                                    }
                                    ?>
                            </select>
                            <button class="btn btn-primary" type="submit" id="searchButton"><i
                                    class="fas fa-search"></i></button>
                        </form>



                        <form action="../php/admin/pending_asign" method="Post"
                            class="d-flex align-items-center ml-auto" id="form_assign">
                            <select name="S_ASIG" class="form-control flex-grow-1 mr-1">
                                <option hidden>Select</option>
                                <?php
                                    $query = "SELECT DISTINCT S_ASIG FROM request";
                                    $result = mysqli_query($conn, $query);

                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $S_ASIG = $row['S_ASIG'];
                                            echo "<option value='$S_ASIG'>$S_ASIG</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options available</option>";
                                    }
                                    ?>
                            </select>
                            <button class="btn btn-primary mr-4" type="button" id="btn_assign">Confirm</button>
                        </form>



                        <form action="../php/admin/pending_report.php" method="POST" id="form_report"
                            class="d-flex align-items-center">
                            <input type="hidden" name="btn_clicked" id="btn_clicked" value="0">

                            <button class="btn btn-info" type="button" id="btn_pending">Generate Report</button>
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
                                <th>Assigned by:</th>
                                <th>Message</th>
                                <th>Deliver/Pick up</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    // Define number of results per page
                    $results_per_page = 10;

                    // Get current page
                    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                        $current_page = intval($_GET['page']);
                    } else {
                        $current_page = 1;
                    }

                    // Calculate offset for LIMIT clause
                    $offset = ($current_page - 1) * $results_per_page;

                    // Check if a search query is provided
                    if (isset($_GET['search'])) {
                        $search = $_GET['search'];

                        // Modify the SQL query to include a WHERE clause for searching
                        $query = "SELECT * FROM request WHERE S_ASIG LIKE '%$search%' OR documentType LIKE '%$search%'";
                    } else {
                        // If no search query provided, use the original SQL query
                        $query = "SELECT * FROM request LIMIT $results_per_page OFFSET $offset";
                    }

                    // Get total number of results (without LIMIT)
                    $total_results_query = "SELECT COUNT(*) as total FROM request";
                    $total_results_result = mysqli_query($conn, $total_results_query);
                    $total_results = mysqli_fetch_assoc($total_results_result)['total'];

                    // Calculate total number of pages
                    $total_pages = ceil($total_results / $results_per_page);

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        $i = 1;
                        while ($users = mysqli_fetch_assoc($result)) {
                                    ?>
                            <tr data-toggle="modal" data-target="#recordModal<?= $i; ?>">
                                <td><?= $i++ ?></td>
                                <td><?= $users['S_FULL'] ?></td>
                                <td><?= $users['S_COU'] ?></td>
                                <td><?= $users['S_YEAR'] ?></td>
                                <td><?= $users['documentType'] ?> <br> <?= $users['documentType_2'] ?> <br>
                                    <?= $users['documentType_3'] ?></td>
                                <td><?= $users['numCopies'] ?> <br> <?= $users['numCopies_2'] ?> <br>
                                    <?= $users['numCopies_3'] ?></td>
                                <td><?= $users['price'] ?></td>
                                <td><?= $users['S_CODE'] ?></td>
                                <td style="color: darkgreen;"><?= $users['S_ASIG'] ?></td>
                                <td id="S_MES_<?= $i; ?>"><?= $users['S_MES']; ?></td>
                                <td><?= $users['S_DEL'] ?></td>
                                <td><?= $users['S_DATE'] ?></td>
                            </tr>
                            <script>
                            const S_MES_<?= $i; ?> = document.getElementById("S_MES_<?= $i; ?>");
                            const messageContent_<?= $i; ?> = S_MES_<?= $i; ?>.textContent.trim();

                            if (messageContent_<?= $i; ?>.toLowerCase() === 'already update' ||
                                messageContent_<?= $i; ?>.toLowerCase() === 'fully paid' ||
                                messageContent_<?= $i; ?>.toLowerCase() === 'send proof of payment') {
                                S_MES_<?= $i; ?>.style.color = "green";
                            } else {
                                S_MES_<?= $i; ?>.style.color = "orange";
                            }
                            </script>
                            <?php
                                }
                            } else {
                                echo "<h5> No Record Found </h5>";
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="12">
                                    <div class="d-flex justify-content-center">
                                        <ul class="pagination">
                                            <?php if ($current_page > 1) : ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="?page=<?= $current_page - 1 ?><?= isset($_GET['search']) ? $_GET['search'] : '' ?>"
                                                    tabindex="-1" aria-disabled="true">
                                                    &lt;&lt;
                                                </a>
                                            </li>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                            <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                                <a class="page-link"
                                                    href="?page=<?= $i ?><?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                            <?php endfor; ?>

                                            <?php if ($current_page < $total_pages) : ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="?page=<?= $current_page + 1 ?><?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
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
                                <p><strong>Name:</strong> <?= $users['S_FULL']; ?></p>
                                <p><strong>Student ID:</strong> <?= $users['S_SID']; ?></p>
                                <p><strong>Course:</strong> <?= $users['S_COU']; ?></p>
                                <p><strong>Year:</strong> <?= $users['S_YEAR']; ?></p>
                                <p><strong>If under Graduation:</strong> <?= $users['S_UND']; ?></p>
                                <p><strong>Company:</strong> <?= $users['S_COM']; ?></p>
                                <p><strong>Position:</strong> <?= $users['S_POS']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Request (1):</strong> <?= $users['documentType']; ?> -
                                    <?= $users['numCopies']; ?></p>
                                <p><strong>Request (2):</strong> <?= $users['documentType_2']; ?> -
                                    <?= $users['numCopies_2']; ?></p>
                                <p><strong>Request (3):</strong> <?= $users['documentType_3']; ?> -
                                    <?= $users['numCopies_3']; ?></p>
                                <p><strong>First request:</strong> <?= $users['firstRequest']; ?></p>
                                <p><strong>Price:</strong> <?= $users['price']; ?></p>
                                <p><strong>Reference No:</strong> <?= $users['S_CODE']; ?></p>
                                <p><strong>Date of request:</strong> <?= $users['S_DATE']; ?></p>
                            </div>
                        </div>

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

    <button class="btn btn-secondary btn-notification position-fixed" style="top: 15px; right: 10px; font-size:12px">
        <i class="fas fa-user-circle mr-2"></i>
        <?php echo htmlspecialchars($_SESSION['R_FIRST'] . ' ' . $_SESSION['R_MIDD'] . ' ' . $_SESSION['R_FULL']); ?>
    </button>
    <footer class="mt-auto" style="background-color: #f2f2f2; text-align: center; padding: 2px 0; font-size: 10px;">
        <p>&copy; 2023 Registrar Management System. All rights reserved.</p>
    </footer>

    <?php include "../php/student/js.php"; ?>
    <?php include "../php/admin/pendingjs.php"; ?>
    


</body>

</html>

<?php
} else {

    header("location: ../signin");
    exit();
}

?>