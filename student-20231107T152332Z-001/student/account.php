<?php
include "../php/dbcon.php";
session_start();
if (isset($_SESSION['R_VERIFIED']) && isset($_SESSION['R_FULL']) && isset($_SESSION['R_EMAIL']) && isset($_SESSION['R_FIRST']) && isset($_SESSION['R_MIDD'])
&& isset($_SESSION['R_STU']) && isset($_SESSION['R_COU']) && isset($_SESSION['R_YEAR']) && isset($_SESSION['R_UN']) && isset($_SESSION['R_POS'])
&& isset($_SESSION['R_COM']) && isset($_SESSION['R_CON']) && isset($_SESSION['R_ADD']) && isset($_SESSION['R_STRE']) && isset($_SESSION['R_BRGY'])
&& isset($_SESSION['R_MUNI']) && isset($_SESSION['R_CITY']) && isset($_SESSION['R_ID']) && isset($_SESSION['R_CODE_ISSUE_TIMESTAMP'])) {

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dasboard.css">
    <link rel="stylesheet" href="../css/nitification.css">
    <title>RMS - Student account</title>
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
                <a href="Dashboard" class="list-group-item list-group-item-action">
                    <i class="fas fa-book mr-2"></i><span class="sidebar-text">Dashboard</span>
                </a>
                <a href="Request" class="list-group-item list-group-item-action">
                    <i class="fas fa-tasks mr-2"></i><span class="sidebar-text">Request List</span>
                </a>
                <a href="pending" class="list-group-item list-group-item-action">
                    <i class="fas fa-hourglass-half mr-2"></i><span class="sidebar-text">Pending Request</span>
                </a>
                <a href="Payment" class="list-group-item list-group-item-action">
                    <i class="fas fa-money-bill-wave mr-2"></i><span class="sidebar-text">Payment</span>
                </a>
                <a href="Onprocess" class="list-group-item list-group-item-action">
                    <i class="fas fa-cogs mr-2"></i><span class="sidebar-text">On Process Request</span>
                </a>
                <a href="Releasing" class="list-group-item list-group-item-action">
                    <i class="fas fa-hourglass-start mr-2"></i><span class="sidebar-text">Releasing Request</span>
                </a>
                <a href="Done" class="list-group-item list-group-item-action">
                    <i class="fas fa-check-circle mr-2"></i><span class="sidebar-text">Done Request</span>
                </a>
                <a href="Account" class="list-group-item list-group-item-action active">
                    <i class="fas fa-user-circle mr-2"></i><span class="sidebar-text">Account</span>
                </a>
                <a href="../php/signout" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt mr-2"></i><span class="sidebar-text">Logout</span>
                </a>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-md-auto col-lg-10 px-1" id="main-content">
            <button id="sidebarToggle" class="btn btn-secondary btn-notification position-fixed"
                style="top: 15px; font-size: 12px"> <i class="fas fa-bars mr2"></i></button>
            <div class="container-fluid">
                <div class="mt-5">
                    <h3 class="mb-4"><i class="fas fa-user-circle mr-2"></i> Account</h3>

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


                    <div class="row">
                        <div class="col-md-8 col-lg-11">
                            <form class="custom-form" action="../php/student/updateaccount.php" method="POST">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- Input for First Name -->
                                        <div class="form-group">
                                            <label for="R_FIRST">First Name</label>
                                            <input type="text" class="form-control" id="R_FIRST"
                                                value="<?php echo ($_SESSION['R_FIRST']); ?>" name="R_FIRST"
                                                placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Input for Middle Name -->
                                        <div class="form-group">
                                            <label for="R_MIDD">Middle Name</label>
                                            <input type="text" class="form-control" id="R_MIDD" name="R_MIDD"
                                                placeholder="Middle Name" value="<?php echo ($_SESSION['R_MIDD']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="R_FULL">Last Name</label>
                                            <input type="text" class="form-control" id="R_FULL"
                                                value="<?php echo ($_SESSION['R_FULL']); ?>" name="R_FULL"
                                                placeholder="Last Name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="R_STU">School ID</label>
                                            <input type="text" class="form-control" id="R_STU" name="R_STU"
                                                value="<?php echo ($_SESSION['R_STU']); ?>" placeholder="Student ID">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Input for Middle Name -->
                                        <div class="form-group">
                                            <label for="R_COU">Course</label>
                                            <input type="text" class="form-control" id="R_COU" name="R_COU"
                                                value="<?php echo ($_SESSION['R_COU']); ?>" placeholder="Course">
                                        </div>
                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label for="R_YEAR">Year</label>
                                            <input type="text" class="form-control" id="R_YEAR"
                                                value="<?php echo ($_SESSION['R_YEAR']); ?>" name="R_YEAR"
                                                placeholder="Year">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="R_CON">Contact Number</label>
                                    <input type="tel" class="form-control" id="R_CON" name="R_CON"
                                        value="<?php echo ($_SESSION['R_CON']); ?>"
                                        placeholder="Contact Number">
                                </div>

                                <div class="row">
                                    <div class="col-md-2">

                                        <div class="form-group">
                                            <label for="R_ADD">House/Unit no.</label>
                                            <input type="number" class="form-control" id="R_ADD"
                                                value="<?php echo ($_SESSION['R_ADD']); ?>" value="Hanter" name="R_ADD"
                                                placeholder="House/Unit no.">
                                        </div>
                                    </div>
                                    <div class="col-md-2">

                                        <div class="form-group">
                                            <label for="R_STRE">Street</label>
                                            <input type="text" class="form-control" id="R_STRE" name="R_STRE"
                                                value="<?php echo ($_SESSION['R_STRE']); ?>" placeholder="Purok/Street">
                                        </div>
                                    </div>
                                    <div class="col-md-2">

                                        <div class="form-group">
                                            <label for="R_BRGY">District</label>
                                            <input type="text" class="form-control" id="R_BRGY"
                                                value="<?php echo ($_SESSION['R_BRGY']); ?>" name="R_BRGY"
                                                placeholder="BRGY/District">
                                        </div>
                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group">
                                            <label for="R_MUNI">City</label>
                                            <input type="text" class="form-control" id="R_MUNI"
                                                value="<?php echo ($_SESSION['R_MUNI']); ?>" name="R_MUNI"
                                                placeholder="City/Munucipality">
                                        </div>
                                    </div>

                                    <div class="col-md-3">

                                        <div class="form-group">
                                            <label for="R_CITY">Region</label>
                                            <input type="text" class="form-control" id="R_CITY"
                                                value="<?php echo ($_SESSION['R_CITY']); ?>"
                                                value="<?php echo ($_SESSION['R_CITY']); ?>" name="R_CITY"
                                                placeholder="Region">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="R_UN">If under graduate semester/year</label>
                                            <input type="text" class="form-control" id="R_UN" name="R_UN"
                                                value="<?php echo ($_SESSION['R_UN']); ?>"
                                                placeholder="If under graduate semester/year">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="R_POS">Position</label>
                                            <input type="text" class="form-control" id="R_POS" name="R_POS"
                                                value="<?php echo ($_SESSION['R_POS']); ?>" placeholder="Position">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="R_COM">Company</label>
                                            <input type="text" class="form-control" id="R_COM" name="R_COM"
                                                value="<?php echo ($_SESSION['R_COM']); ?>" placeholder="Company">
                                        </div>
                                    </div>

                                </div>

                                <button type="button" id="registerButton" class="btn btn-primary">Update now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>

    <button class="btn btn-secondary btn-notification position-fixed" style="top: 15px; right: 40px; font-size:12px">
        <i class="fas fa-user-circle mr-2"></i>
        <?php echo htmlspecialchars($_SESSION['R_FIRST'] . ' ' . $_SESSION['R_MIDD'] . ' ' . $_SESSION['R_FULL']); ?>
    </button>

    <footer class="mt-auto" style="background-color: #f2f2f2; text-align: center; padding: 2px 0; font-size: 10px;">
        <p>&copy; 2023 Registrar Management System. All rights reserved.</p>
    </footer>

    <?php include "../php/student/studentjs.php"; ?>
    <script src="../js/updateallert.js"></script>
    <?php include "../php/student/notification.php"; ?>


</body>

</html>


<?php
} else {

    header("location: ../signin");
    exit();
}

?>