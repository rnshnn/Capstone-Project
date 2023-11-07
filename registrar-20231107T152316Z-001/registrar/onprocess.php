<?php
session_start();
if (isset($_SESSION['R_VERIFIED']) && isset($_SESSION['R_FULL']) && isset($_SESSION['R_EMAIL']) && isset($_SESSION['R_FIRST']) && isset($_SESSION['R_MIDD'])) {

?>

<?php
if (!isset($_SESSION['R_VERIFIED']) || $_SESSION['R_VERIFIED'] != "3") {
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

    <title>RMS - Registrar account</title>
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
                <a href="pending" class="list-group-item list-group-item-action">
                    <i class="fas fa-hourglass-half mr-2"></i> Pending Request
                </a>
                <a href="onprocess/<?= urlencode($registrar_name) ?>"
                    class="list-group-item list-group-item-action active">
                    <i class="fas fa-cogs mr-2"></i> On Process Request
                </a>
                <a href="Releasing" class="list-group-item list-group-item-action">
                    <i class="fas fa-hourglass-start mr-2"></i> Releasing Request
                </a>
                <a href="Done" class="list-group-item list-group-item-action">
                    <i class="fas fa-check-circle mr-2"></i> Done Request
                </a>
                <a href="../php/signout" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>


        </nav>

        <main role="main" class="col-md-9 ml-md-auto col-lg-10 px-1">
            <div class="container-fluid">
                <div class="mt-5">
                    <h3 class="mb-4"> <i class="fas fa-cogs mr-2"></i> On Process</h3>

                    <?php
                $registrar_email = $_SESSION['R_FULL'];
            
                require '../php/dbcon.php';
            
                $query = "SELECT * FROM process WHERE O_ASIG = '$registrar_email'";
                $query_run = mysqli_query($conn, $query);
            
                $row = mysqli_num_rows($query_run);
            
                echo '<h6>You have (' . $row . ') On Process request</h6>';
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
                                <th>Message</th>
                                <th>Deliver/Pick up</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        require '../php/dbcon.php';
                        $registrar_name = $_SESSION['R_FULL'];

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
                            $query = "SELECT COUNT(*) as total FROM process WHERE O_ASIG=? AND CONCAT(O_FULL,O_CODE,O_documentType) LIKE ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ss", $registrar_name, $filtervalues);
                        } else {
                            $query = "SELECT COUNT(*) as total FROM process WHERE O_ASIG=?";
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
                            $query = "SELECT * FROM process WHERE O_ASIG=? AND CONCAT(O_FULL,O_CODE,O_documentType) LIKE ? LIMIT $results_per_page OFFSET $offset";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ss", $registrar_name, $filtervalues);
                        } else {
                            $query = "SELECT * FROM process WHERE O_ASIG=? LIMIT $results_per_page OFFSET $offset";
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
                                <td><?= $users['O_FULL'] ?></td>
                                <td><?= $users['O_COU'] ?></td>
                                <td><?= $users['O_YEAR'] ?></td>
                                <td><?= $users['O_documentType'] ?> <br> <?= $users['O_documentType_2'] ?> <br>
                                    <?= $users['O_documentType_3'] ?></td>
                                <td><?= $users['O_numCopies'] ?> <br> <?= $users['O_numCopies_2'] ?> <br>
                                    <?= $users['O_numCopies_3'] ?></td>
                                <td><?= $users['O_price'] ?></td>
                                <td><?= $users['O_CODE'] ?></td>
                                <td id="S_MES_<?= $i; ?>"><?= $users['S_MES']; ?></td>
                                <td><?= $users['O_DEL'] ?></td>
                                <td><?= $users['O_DATE'] ?></td>
                            </tr>
                            <?php include "../php/registrar/processjs.php"; ?>
                            <?php
                                    }}else{echo "<h5> No Record Found </h5>";} 
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

                    <!-- Print -->
                    <div class="receipt_au" style="display:none;">
                        <div class="receipt">

                            <div class="header">
                                <img src="../img/AU-logo.png" alt="Logo" class="logo img-fluid"><br>
                            </div>

                            <div class="receipt-content">
                                <div class="row">
                                    <div class="col-md-6 label_md6">
                                        <p><strong>Sender:</strong> PHINMA Araullo University</p>
                                        <p><strong>Receiver:</strong> <?= $users['O_FULL']; ?></p>

                                        <div class="qr-code">
                                            <img src="../img/Screenshot 2023-09-03 151558.png" alt="QR Code"
                                                width="100px" height="100px">
                                        </div>
                                    </div>
                                    <div class="col-md-6 label_md6">
                                        <p><strong>Address:</strong> <?= $users['O_ADD']; ?></p>
                                        <p><strong>Contact:</strong> <?= $users['O_NUM']; ?></p>
                                        <p><strong>Request (1):</strong> <?= $users['O_documentType']; ?> -
                                            <?= $users['O_numCopies']; ?></p>
                                        <p><strong>Request (2):</strong> <?= $users['O_documentType_2']; ?> -
                                            <?= $users['O_numCopies_2']; ?></p>
                                        <p><strong>Request (3):</strong> <?= $users['O_documentType_3']; ?> -
                                            <?= $users['O_numCopies_3']; ?></p>

                                        <p><strong>First request:</strong> <?= $users['O_firstRequest']; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 label_md6">
                                        <p><strong>Price:</strong> <?= $users['O_price']; ?></p>
                                        <p><strong>Reference No:</strong> <?= $users['O_CODE']; ?></p>
                                        <p><strong>Date of request:</strong> <?= $users['O_DATE']; ?></p>
                                    </div>
                                    <div class="col-md-6 label_md6">

                                        <p class="additional-info">This is an official receipt issued by PHINMA Araullo
                                            University.</p>
                                    </div>
                                </div>
                            </div>


                            <div class="footer">
                                <p>Thank you for choosing PHINMA Araullo University.</p>
                            </div>
                        </div>
                    </div>
                    <!-- End -->


                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <?= $users['O_FULL']; ?></p>
                                <p><strong>Student ID:</strong> <?= $users['O_SID']; ?></p>
                                <p><strong>Course:</strong> <?= $users['O_COU']; ?></p>
                                <p><strong>Year:</strong> <?= $users['O_YEAR']; ?></p>
                                <p><strong>If under Graduation:</strong> <?= $users['O_UND']; ?></p>
                                <p><strong>Company:</strong> <?= $users['O_COM']; ?></p>
                                <p><strong>Position:</strong> <?= $users['O_POS']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Request (1):</strong> <?= $users['O_documentType']; ?> -
                                    <?= $users['O_numCopies']; ?></p>
                                <p><strong>Request (2):</strong> <?= $users['O_documentType_2']; ?> -
                                    <?= $users['O_numCopies_2']; ?></p>
                                <p><strong>Request (3):</strong> <?= $users['O_documentType_3']; ?> -
                                    <?= $users['O_numCopies_3']; ?></p>
                                <p><strong>First request:</strong> <?= $users['O_firstRequest']; ?></p>
                                <p><strong>Price:</strong> <?= $users['O_price']; ?></p>
                                <p><strong>Reference No:</strong> <?= $users['O_CODE']; ?></p>
                                <p><strong>Date of request:</strong> <?= $users['O_DATE']; ?></p>
                                <p><strong>PDF file</strong>
                                <form action="../php/registrar/processdonwload" method="post"
                                    style="display: inline-block; margin-right: 20px;">
                                    <input type="hidden" name="fileInput" value="<?= $users['O_fileInput']; ?>">
                                    <button type="submit" name="Download_fileInput" class="btn btn-success btn-sm">
                                        Download File <i class="uil uil-import"></i>
                                    </button>
                                </form>
                                <button type="button" name="print_receit" id="print-button<?= $i; ?>"
                                    class="btn btn-primary btn-sm">
                                    Print receipt<i class="uil uil-print"></i>
                                </button>
                                </p>
                            </div>
                        </div>

                        <script>
                        // Add a click event listener to all print buttons
                        const printButton<?= $i; ?> = document.getElementById('print-button<?= $i; ?>');
                        printButton<?= $i; ?>.addEventListener('click', (e) => {
                            e.preventDefault();
                            // Get the corresponding modal for the clicked button
                            const modalId = `#recordModal<?= $i; ?>`;
                            const modal = document.querySelector(modalId);

                            // Create a style element for print-specific styles
                            const printStyles = `
                                /* Add your custom print styles here */
                                .receipt {
                                    border: 1px solid #000;
                                    padding: 20px;
                                    margin: 20px;
                                }
                                .logo {
                                    /* Add your logo styling here */
                                    width: 300px;
                                    height: auto;
                                }
                                .header {
                                    text-align: center;
                                    font-size: 24px;
                                    font-weight: bold;
                                    margin-bottom: 20px;
                                }
                                .receipt-content {
                                    border-top: 1px solid #000;
                                    margin-top: 20px;
                                    padding-top: 10px;
                                }
                                .row {
                                    margin-top: 10px;
                                    display: flex;
                                }
                                .label_md6 {
                                    flex: 1;
                                    border-right: 1px solid #000;
                                    padding-right: 10px;
                                    padding-left: 10px;
                                }
                                .additional-info {
                                    font-style: italic;
                                    margin-top: 20px;
                                }
                                .footer {
                                    text-align: center;
                                    margin-top: 20px;
                                }   
                                .qr-code {
                                    text-align: center;
                                    margin-top: 20px;
                                }
                                .qr-code img {
                                    width: 100px;
                                    height: 100px;
                                }
                            `;

                            // Append the print-specific styles to the modal content
                            const printWindow = window.open('', '', 'width=600,height=600');
                            printWindow.document.open();
                            printWindow.document.write('<html><head><title>Print</title></head><body>');
                            printWindow.document.write('<style>' + printStyles + '</style>');
                            printWindow.document.write(modal.querySelector('.receipt_au').innerHTML);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();

                            // Print and close the print window
                            printWindow.print();
                            printWindow.close();
                        });
                        </script>

                        <div class="mt-4">
                            <p><strong>Action:</strong></p>
                            <form action="../php/registrar/processupdate" method="post" class="mt-2"
                                id="returnForm<?= $i; ?>" enctype="multipart/form-data">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <select class="form-control" id="update_mes<?= $i; ?>" name="S_MES">
                                            <option hidden>Select</option>
                                            <option value="processing request">Processing request</option>
                                        </select>

                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-info btn-sm" type="button"
                                            id="submitRequestButton<?= $i; ?>"
                                            <?= strtolower($users['S_MES']) === 'processing request' ? 'disabled' : '' ?>>
                                            Submit
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="request_id" value="<?= $users['O_ID']; ?>">
                            </form>

                            <div class="d-flex justify-content-center mt-2" style="width: 100%;">
                                <form action="../php/registrar/processfinish.php" method="post"
                                    id="approveForm<?= $i; ?>" style="width: 100%; margin-top:10px;">
                                    <input type="hidden" name="processfinish" value="<?= $users['O_ID']; ?>">
                                    <input type="hidden" name="O_DEL" value="<?= $users['O_DEL']; ?>">
                                    <button style="width: 100%;  padding: 10px 0;" class="btn btn-primary btn-sm"
                                        type="button" onclick="showApproveDialog(<?= $i; ?>)"
                                        <?= strtolower($users['S_MES']) === '' || $users['S_MES'] === 'waiting'? 'disabled' : '' ?>>
                                        Process finish
                                    </button>
                                </form>
                            </div>

                            <?php include "../php/registrar/processjs.php"; ?>
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


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/sweetalert2.all.min.js"></script>
    <?php include "../php/registrar/notification.php"; ?>

</body>

</html>

<?php
} else {

    header("location: ../signin");
    exit();
}

?>