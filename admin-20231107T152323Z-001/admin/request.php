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

    <title>RMS - Admin Request account</title>
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
                <a href="user" class="list-group-item list-group-item-action ">
                    <i class="fas fa-users mr-2"></i> User
                    Account&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
                        class="fas fa-caret-right "></i>
                </a>
                <a href="request" class="list-group-item list-group-item-action active">
                    <i class="fas fa-user-plus mr-2"></i> Request Account
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
                        class="fas fa-caret-right "></i>
                </a>
                <a href="student" class="list-group-item list-group-item-action">
                    <i class="fas fa-graduation-cap mr-2"></i> Student Account
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
                        class="fas fa-caret-right "></i>
                </a>
                <a href="pending" class="list-group-item list-group-item-action">
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
                <a href="done" class="list-group-item list-group-item-action">
                    <i class="fas fa-check-circle mr-2"></i> Done Request
                </a>
                <a href="decline" class="list-group-item list-group-item-action">
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
                    <h3 class="mb-4"> <i class="fas fa-user-plus mr-2"></i> Request Account</h3>

                    <?php
                        $query = "SELECT COUNT(*) AS total FROM register WHERE R_RORA = ''";
                        $query_run = mysqli_query($conn, $query);

                        if ($query_run) {
                            $row = mysqli_fetch_assoc($query_run);
                            $row_count = $row['total'];

                            echo '<h6>We have (' . $row_count . ') Requested Accounts</h6>';
                        } else {
                            echo "Query failed: " . mysqli_error($conn);
                        }
                        ?>

                    <div class="input-group mb-3">
                        <form action="" method="GET" class="w-50 d-flex align-items-center">
                            <input type="text" name="search" class="form-control flex-grow-1" placeholder="Search Name"
                                aria-label="Search input" aria-describedby="searchButton">
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
                                <th>Email</th>
                                <th>Password</th>
                                <th>School ID</th>
                                <th>Course</th>
                                <th>Year</th>
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

                        $query = "SELECT * FROM register WHERE (R_RORA IS NULL OR R_RORA = '') AND R_FULL LIKE '%$search%'";

                    } else {
                        $query = "SELECT * FROM register WHERE (R_RORA IS NULL OR R_RORA = '') LIMIT $results_per_page OFFSET $offset";

                    }

                    // Get total number of results (without LIMIT)
                    $total_results_query = "SELECT COUNT(*) as total FROM register WHERE (R_RORA IS NULL OR R_RORA = '')";
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
                                <td><?= $users['R_FULL'] ?> <?= $users['R_MIDD'] ?> <?= $users['R_FIRST'] ?> <br> &nbsp;</td>
                                <td><?= $users['R_EMAIL'] ?></td>
                                <td>Keep password secure at all times.</td>
                                <td><?= $users['R_STU'] ?></td>
                                <td><?= $users['R_COU'] ?></td>
                                <td><?= $users['R_YEAR'] ?></td>
                                <td><?= $users['R_DATE'] ?></td>
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
                                <p><strong>Name:</strong> <?= $users['R_FULL'] ?> <?= $users['R_MIDD'] ?>
                                    <?= $users['R_FIRST'] ?></p>
                                <p><strong>Email:</strong> <?= $users['R_EMAIL']; ?></p>
                                <p><strong>Password:</strong> Keep password secure at all times.</p>
                                <p><strong>School ID:</strong> <?= $users['R_STU']; ?></p>
                                <p><strong>Course:</strong> <?= $users['R_COU']; ?></p>
                                <p><strong>Year:</strong> <?= $users['R_YEAR']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>If Under Graduate:</strong> <?= $users['R_UN']; ?></p>
                                <p><strong>Company:</strong> <?= $users['R_COM']; ?></p>
                                <p><strong>Position:</strong> <?= $users['R_POS']; ?>
                                <p  style="display: inline-block;"><strong >Proof of Identity:</strong>
                                <form action="../php/registrar/pendingdownload.php" method="post"
                                    style="display: inline-block; margin-right: 20px;">
                                    <input type="hidden" name="fileInput" value="<?= $users['R_IMG']; ?>">
                                    <button type="submit" name="Download_fileInput" class="btn btn-success btn-sm">
                                        Download File <i class="uil uil-import"></i>
                                    </button>
                                </form>
                                </p>

                            </div>
                        </div>

                        <div class="mt-4">
                            <p><strong>OTHER INFORMATION</strong></p>
                            <div class="col-md-6">
                                <p><strong>Contact Number:</strong> <?= $users['R_CON']; ?></p>
                                <p><strong>House No:</strong> <?= $users['R_ADD']; ?></p>
                                <p><strong>Street:</strong> <?= $users['R_STRE']; ?></p>
                                <p><strong>District:</strong> <?= $users['R_BRGY']; ?></p>
                                <p><strong>City:</strong> <?= $users['R_MUNI']; ?></p>
                                <p><strong>Region:</strong> <?= $users['R_CITY']; ?></p>
                            </div>
                        </div>
                        <!-- Approve and Return Forms -->
                        <div class="mt-4">
                         
                            <div class="modal-footer">
                            <p><strong>Action </strong></p>

                                <form action="../php/admin/declined" method="post" class="mt-2"
                                    id="declined_id<?= $i; ?>" enctype="multipart/form-data">
                                    <button type="button" id="declined_button<?= $i; ?>" class="btn btn-danger ml-2"
                                        onclick="confirmDeclined(<?= $i; ?>)">Declined</button>
                                    <input type="hidden" name="request_id" value="<?= $users['R_ID']; ?>">
                                </form>

                                <form action="../php/admin/approve" method="post" class="mt-2" id="approve_id<?= $i; ?>"
                                    enctype="multipart/form-data">
                                    <button type="button" id="approve_button<?= $i; ?>" class="btn btn-success ml-2"
                                        onclick="confirmApprove(<?= $i; ?>)">Approve</button>
                                    <input type="hidden" name="request_id" value="<?= $users['R_ID']; ?>">
                                </form>
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

    <script>
    function confirmDeclined(i) {
        Swal.fire({
            title: 'Confirm Declined',
            text: 'Are you sure you want to decline this request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, decline it',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`declined_id${i}`).submit();
            }
        });
    }

    function confirmApprove(i) {
        Swal.fire({
            title: 'Confirm Approve',
            text: 'Are you sure you want to approve this request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`approve_id${i}`).submit();
            }
        });
    }
</script>






</body>

</html>

<?php
} else {

    header("location: ../signin");
    exit();
}

?>