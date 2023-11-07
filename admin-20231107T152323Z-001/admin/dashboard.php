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

    <title>RMS - Admin dasboard</title>
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
                <a href="Dashboard" class="list-group-item list-group-item-action active">
                    <i class="fas fa-book mr-2"></i> Dashboard
                </a>
                <a href="user" class="list-group-item list-group-item-action">
                    <i class="fas fa-users mr-2"></i> User Account&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-caret-down "></i>
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

        <main role="main" class="col-md-9 ml-md-auto col-lg-10 px-2">
            <div class="container-fluid">
                <div class="my-5">
                    <h3 class="mb-4"><i class="fas fa-book mr-2"></i>Dashboard</h3>


                    <div class="row">
                        <!-- Pending Requests -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="#" class="text-decoration-none">
                                <div class="border rounded p-2">
                                    <h5 class="card-title">Pending Requests</h5>
                                    <div class="card shadow-sm bg-primary text-white">
                                        <div class="card-body">
                                            <p class="card-text" style="font-size: 24px;">4</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Payment Requests -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="#" class="text-decoration-none">
                                <div class="border rounded p-2">
                                    <h5 class="card-title">Payment Requests</h5>
                                    <div class="card shadow-sm bg-success text-white">
                                        <div class="card-body">
                                            <p class="card-text" style="font-size: 24px;">2</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- On Process Requests -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="#" class="text-decoration-none">
                                <div class="border rounded p-2">
                                    <h5 class="card-title">On Process Requests</h5>
                                    <div class="card shadow-sm bg-warning text-dark">
                                        <div class="card-body">
                                            <p class="card-text" style="font-size: 24px;">3</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Releasing Requests -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="#" class="text-decoration-none">
                                <div class="border rounded p-2">
                                    <h5 class="card-title">Releasing Requests</h5>
                                    <div class="card shadow-sm bg-info text-white">
                                        <div class="card-body">
                                            <p class="card-text" style="font-size: 24px;">1</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Done Requests -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="#" class="text-decoration-none">
                                <div class="border rounded p-2">
                                    <h5 class="card-title">Done Requests</h5>
                                    <div class="card shadow-sm bg-secondary text-white">
                                        <div class="card-body">
                                            <p class="card-text" style="font-size: 24px;">6</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Decline Requests -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="#" class="text-decoration-none">
                                <div class="border rounded p-2">
                                    <h5 class="card-title">Decline Requests</h5>
                                    <div class="card shadow-sm bg-danger text-white">
                                        <div class="card-body">
                                            <p class="card-text" style="font-size: 24px;">0</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>



                    <div class="form-group mt-4">
                        <textarea class="form-control" rows="6" placeholder="Announcements"></textarea>
                        <button type="sumbit" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </div>
        </main>



    </div>

    <button class="btn btn-secondary btn-notification position-fixed" style="top: 15px; right: 10px; font-size:12px">
        <i class="fas fa-user-circle mr-2"></i>
        <?php echo htmlspecialchars($_SESSION['R_FIRST'] . ' ' . $_SESSION['R_MIDD'] . ' ' . $_SESSION['R_FULL']); ?>
    </button>
    <footer class="mt-auto" style="background-color: #f2f2f2; text-align: center; padding: 2px 0; font-size: 10px;">
        <p>&copy; 2023 Registrar Management System. All rights reserved.</p>
    </footer>

    <?php include "../php/student/js.php"; ?>
</body>

</html>

<?php
} else {

    header("location: ../signin");
    exit();
}

?>