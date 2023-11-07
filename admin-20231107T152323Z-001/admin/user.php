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

    <title>RMS - Admin user account</title>
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
                <a href="user" class="list-group-item list-group-item-action active">
                    <i class="fas fa-users mr-2"></i> User
                    Account&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
                        class="fas fa-caret-right "></i>
                </a>
                <a href="request" class="list-group-item list-group-item-action">
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
                    <h3 class="mb-4"> <i class="fas fa-users mr-2"></i> User Account</h3>

                    <?php
                            $query = "SELECT COUNT(*) AS total FROM register WHERE R_VERIFIED IN (4, 3, 5)";
                            $query_run = mysqli_query($conn, $query);
                            
                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run);
                                $row_count = $row['total'];
                                
                                echo '<h6>We have (' . $row_count . ') User Accounts</h6>';
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

                        <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#addAccountModal">Add
                            Account</button>


                        <!-- Modal -->

                        <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog"
                            aria-labelledby="addAccountModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-xl-custom" style="max-width: 80%;" role="document">
                                <!-- Use modal-lg for a large modal -->
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title" id="addAccountModalLabel">Add Account</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="../php/admin/admin_registrar_account" method="POST"
                                            enctype="multipart/form-data" id="user_account" class="custom-form">
                                            <div class="form-row add_one">
                                                <div class="form-group col-md-2">
                                                    <label for="R_FULL">Name</label>
                                                    <input type="text" name="accounts[0][R_FULL]" class="form-control"
                                                        placeholder="Enter name" id="R_FULL">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="R_STU">School ID</label>
                                                    <input type="text" name="accounts[0][R_STU]" class="form-control"
                                                        placeholder="Enter school ID" id="R_STU">
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label for="R_EMAIL">Email</label>
                                                    <input type="email" name="accounts[0][R_EMAIL]" class="form-control"
                                                        placeholder="Enter email" id="R_EMAIL">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="R_COM">Company</label>
                                                    <input type="text" name="accounts[0][R_COM]" class="form-control"
                                                        placeholder="Enter company" id="R_COM">
                                                </div>

                                                <div class="form-group col-md-2" style="display:none">
                                                    <label for="R_POS">Position</label>
                                                    <input type="text" name="accounts[0][R_POS]" class="form-control"
                                                        placeholder="Enter position" id="R_POS">
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label for="R_PASS">Password</label>
                                                    <input type="password" name="accounts[0][R_PASS]"
                                                        class="form-control" placeholder="Enter password" id="R_PASS">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="R_VERIFIED">Role</label>
                                                    <select name="accounts[0][R_VERIFIED]" class="form-control"
                                                        id="R_VERIFIED">
                                                        <option hidden></option>
                                                        <option value="3">Registrar</option>
                                                        <option value="4">Admin</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2" style="display:none">
                                                    <select name="accounts[0][R_RORA]" class="form-control" id="R_RORA">
                                                        <option value="approve">1</option>

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2" style="display:none">
                                                    <input type="text" name="accounts[0][R_SMS]" class="form-control"
                                                        id="R_SMS" value="Enable">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success ml-3"
                                                    id="addField">+1</button>
                                                <button type="button" id="registerButton"
                                                    class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->

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
                                <th>School ID</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Position</th>
                                <th>Action</th>
                                <th>Status</th>
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
                        $query = "SELECT * FROM register WHERE R_VERIFIED IN (4, 3, 5) AND R_FULL LIKE '%$search%'";

                    } else {
                        // If no search query provided, use the original SQL query
                        $query = "SELECT * FROM register WHERE R_VERIFIED IN (4, 3, 5) LIMIT $results_per_page OFFSET $offset";
                    }

                    // Get total number of results (without LIMIT)
                    $total_results_query = "SELECT COUNT(*) as total FROM register WHERE R_VERIFIED IN (4, 3, 5)";
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
                                <td><?= $users['R_FULL'] ?> <br> &nbsp;</td>
                                <td><?= $users['R_STU'] ?></td>
                                <td><?= $users['R_EMAIL'] ?></td>
                                <td><?= $users['R_COM'] ?></td>
                                <td class="user-pos"><?= $users['R_POS'] ?></td>
                                <td class="user-pos"><?= $users['R_SMS'] ?></td>
                                <td class="user-pos"><?= $users['R_STATUS'] ?></td>
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
                        <h5 class="modal-title" id="recordModalLabel">Account Record Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <?= $users['R_FULL']; ?></p>
                                <p><strong>School ID:</strong> <?= $users['R_STU']; ?></p>
                                <p><strong>Email:</strong> <?= $users['R_EMAIL']; ?></p>
                                <p><strong>Company:</strong> <?= $users['R_COM']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Possition:</strong> <?= $users['R_POS']; ?></p>
                                <p><strong>Action:</strong> <?= $users['R_SMS']; ?></p>
                                <p><strong>Status:</strong> <?= $users['R_STATUS']; ?></p>
                            </div>
                        </div>

                        <!-- Approve and Return Forms -->
                        <div class="mt-4">
                            <p><strong>Action </strong></p>
                            <div class="modal-footer">


                                <form action="../php/admin/disable" method="post" class="mt-2"
                                    id="disabled_id<?= $i; ?>" enctype="multipart/form-data">
                                    <button type="button" id="disable_button<?= $i; ?>" class="btn btn-danger ml-2"
                                        onclick="confirmDisable(<?= $i; ?>)">Disable</button>
                                    <input type="hidden" name="request_id" value="<?= $users['R_ID']; ?>">
                                </form>

                                <form action="../php/admin/enable" method="post" class="mt-2" id="enabled_id<?= $i; ?>"
                                    enctype="multipart/form-data">
                                    <button type="button" id="enable_button<?= $i; ?>" class="btn btn-success ml-2"
                                        onclick="confirmEnable(<?= $i; ?>)">Enable</button>
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
    <?php include "../php/admin/accountjs.php"; ?>





</body>

</html>

<?php
} else {

    header("location: ../signin");
    exit();
}

?>