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

    <title>RMS - Student request</title>
</head>

<body style="display: flex; flex-direction: column; min-height: 100vh;">
    <header>
        <!-- Header content goes here -->
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
                <a href="Request" class="list-group-item list-group-item-action active">
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
                <a href="Account" class="list-group-item list-group-item-action">
                    <i class="fas fa-user-circle mr-2"></i><span class="sidebar-text">Account</span>
                </a>
                <a href="../php/signout" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt mr-2"></i><span class="sidebar-text">Logout</span>
                </a>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-md-auto col-lg-10 px-1">
            <button id="sidebarToggle" class="btn btn-secondary btn-notification position-fixed"
                style="top: 15px; font-size: 12px"> <i class="fas fa-bars mr2"></i></button>
            <div class="container-fluid">
                <div class="mt-5">
                    <h3 class="mb-4"><i class="fas fa-tasks mr-2"></i> Request</h3>

                    <div class="text-left mt-3">
                        <p><i class="fas fa-exclamation-circle"></i> Please check your <a href="account"> 'Account
                                information'</a> before requesting a document.</p>
                    </div>

                    <form id="requestForm" enctype="multipart/form-data" action="../php/request" method="post">

                        <?php if(isset($_GET['error'])){ ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $_GET['error']; ?>
                        </div>
                        <?php } ?>

                        <?php if(isset($_GET['success'])){ ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $_GET['success']; ?>
                        </div>
                        <?php }?>

                        <div style="display:none;">
                            <input type="text" name="S_FULL"
                                value="<?php echo htmlspecialchars($_SESSION['R_FIRST'] . ' ' . $_SESSION['R_MIDD'] . ' ' . $_SESSION['R_FULL']); ?>"
                                readonly>
                            <input type="text" name="S_EMAIL" value="<?php echo ($_SESSION['R_EMAIL'])?>" readonly>

                            <input type="text" name="S_SID" value="<?php echo ($_SESSION['R_STU'])?>" readonly>
                            <input type="text" name="S_COU" value="<?php echo ($_SESSION['R_COU'])?>" readonly>
                            <input type="text" name="S_YEAR" value="<?php echo ($_SESSION['R_YEAR'])?>" readonly>
                            <input type="text" name="S_UND" value="<?php echo ($_SESSION['R_UN'])?>" readonly>
                            <input type="text" name="S_POS" value="<?php echo ($_SESSION['R_POS'])?>" readonly>
                            <input type="text" name="S_COM" value="<?php echo ($_SESSION['R_COM'])?>" readonly>
                            <input type="text" name="S_NUM" value="<?php echo ($_SESSION['R_CON'])?>" readonly>
                            <input type="text" name="S_ADD"
                                value="<?php echo ($_SESSION['R_ADD'] . ' ' . $_SESSION['R_STRE'] . ' ' . $_SESSION['R_BRGY'] . ' ' . $_SESSION['R_MUNI'] . ' ' . $_SESSION['R_CITY']); ?>"
                                readonly>
                        </div>

                        <!-- first request -->
                        <div class="form-group">
                            <label for="documentType">Select Document Request Type:</label>
                            <select class="form-control" id="documentType" name="documentType">
                                <option hidden>select</option>
                                <option value="TOR for reference">TOR for reference </option>
                                <option value="TOR for board exam">TOR for board exam</option>
                                <option value="TOR for copy school">TOR for copy school</option>
                                <option value="FORM - 137">Form 137 For Reference</option>
                                <option value="HONOROBALE DISMISSAL">HONOROBALE DISMISSAL (HD)</option>
                                <option value="CAV">CERTIFICATION, AUTHENTICATION, AND VERIFICATION (CAV) </option>
                                <option value="Authentication">AUTHENTICATION</option>
                                <option value="Certificate of Unit Earned">Certificate of Unit Earned</option>
                                <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                                <option value="Certificate of Graduation">Certificate of Graduation</option>
                                <option value="Certificate of Grades">Certificate of Grades</option>
                                <option value="Certificate of Enrolled Semester">Certificate of Enrolled Semester
                                </option>
                                <option value="Certificate of English Proficiency">Certificate of English Proficiency
                                </option>
                                <option value="Certificate of NSTP Serial Number">Certificate of NSTP Serial Number
                                </option>
                                <option value="Certificate of Honor">Certificate of Honor</option>
                                <option value="Subject Description">Subject Description</option>
                                <option value="diploma">diploma</option>


                            </select>
                        </div>

                        <div class="form-group" id="numCopiesContainer" style="display: none;">
                            <label for="numCopies">Number of Copies:</label>
                            <select class="form-control" id="numCopies" name="numCopies">
                                <option value="1">1 Copy</option>
                                <option value="2">2 Copies</option>
                                <option value="3">3 Copies</option>

                            </select>
                            <input type="text" id="total_1" style="display: none;">
                        </div>
                        <!-- first request -->
                        <div id="hide">
                            <!-- second request -->
                            <div class="form-group" id="secondRequestContainer"
                                style="display: none; border-top: 2px solid #000; padding-top: 6px">
                                <label for="documentType_2">Select Document Request Type (Request 2):</label>
                                <select class="form-control" id="documentType_2" name="documentType_2">
                                    <option hidden></option>
                                    <option value="TOR for reference">TOR for reference </option>
                                    <option value="TOR for board exam">TOR For Board Exam</option>
                                    <option value="TOR for copy school">TOR Copy for School</option>
                                    <option value="FORM - 137">Form 137 For Reference</option>
                                    <option value="HONOROBALE DISMISSAL">HONOROBALE DISMISSAL (HD)</option>
                                    <option value="CAV">CERTIFICATION, AUTHENTICATION, AND VERIFICATION (CAV) </option>
                                    <option value="Authentication">AUTHENTICATION</option>
                                    <option value="Certificate of Unit Earned">Certificate of Unit Earned</option>
                                    <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                                    <option value="Certificate of Graduation">Certificate of Graduation</option>
                                    <option value="Certificate of Grades">Certificate of Grades</option>
                                    <option value="Certificate of Enrolled Semester">Certificate of Enrolled Semester
                                    </option>
                                    <option value="Certificate of English Proficiency">Certificate of English
                                        Proficiency
                                    </option>
                                    <option value="Certificate of NSTP Serial Number">Certificate of NSTP Serial Number
                                    </option>
                                    <option value="Certificate of Honor">Certificate of Honor</option>
                                    <option value="Subject Description">Subject Description</option>
                                    <option value="diploma">diploma</option>
                                </select>
                            </div>

                            <div class="form-group" id="numCopiesContainer_2"
                                style="display: none; border-bottom: 2px solid #000; padding-bottom: 10px ">
                                <label for="numCopies_2">Number of Copies (Request 2):</label>
                                <select class="form-control" id="numCopies_2" name="numCopies_2">
                                    <option hidden></option>
                                    <option value="1">1 Copy</option>
                                    <option value="2">2 Copies</option>
                                    <option value="3">3 Copies</option>
                                </select>
                                <input type="text" id="total_2" style="display: none;">
                            </div>
                            <!-- second request -->

                            <!-- third request -->
                            <div class="form-group" id="thirdRequestContainer" style="display: none;">
                                <label for="documentType_3">Select Document Request Type (Request 3):</label>
                                <select class="form-control" id="documentType_3" name="documentType_3">
                                    <option hidden></option>
                                    <option value="TOR for reference">TOR for reference </option>
                                    <option value="TOR for board exam">TOR For Board Exam</option>
                                    <option value="TOR for copy school">TOR Copy for School</option>
                                    <option value="FORM - 137">Form 137 For Reference</option>
                                    <option value="HONOROBALE DISMISSAL">HONOROBALE DISMISSAL (HD)</option>
                                    <option value="CAV">CERTIFICATION, AUTHENTICATION, AND VERIFICATION (CAV) </option>
                                    <option value="Authentication">AUTHENTICATION</option>
                                    <option value="Certificate of Unit Earned">Certificate of Unit Earned</option>
                                    <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                                    <option value="Certificate of Graduation">Certificate of Graduation</option>
                                    <option value="Certificate of Grades">Certificate of Grades</option>
                                    <option value="Certificate of Enrolled Semester">Certificate of Enrolled Semester
                                    </option>
                                    <option value="Certificate of English Proficiency">Certificate of English
                                        Proficiency
                                    </option>
                                    <option value="Certificate of NSTP Serial Number">Certificate of NSTP Serial Number
                                    </option>
                                    <option value="Certificate of Honor">Certificate of Honor</option>
                                    <option value="Subject Description">Subject Description</option>
                                    <option value="diploma">diploma</option>
                                </select>
                            </div>

                            <div class="form-group" id="numCopiesContainer_3"
                                style="display: none; border-bottom: 2px solid #000; padding-bottom: 10px ">
                                <label for="numCopies_3">Number of Copies (Request 3):</label>
                                <select class="form-control" id="numCopies_3" name="numCopies_3">
                                <option hidden></option>
                                    <option value="1">1 Copy</option>
                                    <option value="2">2 Copies</option>
                                    <option value="3">3 Copies</option>
                                </select>
                            </div>
                            <!-- third request -->
                            <input type="text" id="total_3" style="display:none ;">
                        </div>


                        <div class="form-group" id="firstRequestContainer" style="display: none;">
                            <label for="firstRequest">Is this your first request?</label>
                            <select class="form-control" id="firstRequest" name="firstRequest">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>


                        <div class="form-group" id="deliver" style="display: none;">
                            <label for="S_DEL">Deliver/Pick up</label>
                            <select class="form-control" id="S_DEL" name="S_DEL">
                                <option value="pick up">Pick up</option>
                                <option value="deliver">Deliver</option>
                            </select>
                        </div>



                        <div class="form-group mt-4" id="torCopyContainer" style="display: none;">
                            <b>TOR Copy for School:</b> <br>
                            <div class="input-box">
                                <b>Request Letter from Current School (please upload the scanned copy) </b><br>
                                Note:
                                We will not process your request without the clear scanned copy (PDF) of the Request
                                Letter of your current school.
                            </div>
                        </div>

                        <div class="form-group mt-4" id="torExamContainer" style="display: none;">
                            <b>TOR For Board Exam:</b> <br>
                            <div class="input-box">
                                <b>Requirements: </b> <br>
                                a. Passport size picture <br>
                                b. Colored, with white background <br>
                                c. Taken in full-face view directly facing the camera <br>
                                d. With neutral facial expression, and both eyes open <br>
                                e. With HANDWRITTEN (not computer-generated) name tag legibly showing PRINTED FULL
                                NAME
                                in the
                                format: First Name, Middle Initial, Last Name and Extension Name, if any
                            </div>
                        </div>
                        <div class="form-group mt-4" id="form137Container" style="display: none;">
                            <b>Form 137 For Reference:</b> <br>
                            <div class="input-box">
                                <b>Request Letter from Current School </b> <br> Note: We will not process your
                                request
                                without
                                the clear scanned copy (PDF) of the Request Letter of your current school.
                            </div>
                        </div>
                        <div class="form-group mt-4" id="dismissalContainer" style="display: none;">
                            <b> HONOROBALE DISMISSAL (HD) :</b> <br>
                            <div class="input-box">
                                <b> Upload your Withdrawal Form </b> <br> Note: We will not process your request
                                without the
                                clear
                                scanned copy (PDF) of your Withdrawal Form.
                            </div>
                        </div>

                        <div class="form-group mt-4" id="cavContainer" style="display: none;">
                            <b>CERTIFICATION, AUTHENTICATION, AND VERIFICATION (CAV):</b> <br>
                            <div class="input-box">
                                <b>Please Attach a clear scanned copy (PDF) of your TOR and Diploma (FOR CAV
                                    REQUEST
                                    ONLY)</b>
                                <br>
                                Note: We will not process your CAV request without the clear scanned copy (PDF) of
                                your
                                TOR and
                                Dimploma.


                            </div>
                        </div>

                        <div class="form-group mt-4" id="authContainer" style="display: none;">
                            <b>AUTHENTICATION:</b> <br>
                            <div class="input-box">
                                <b>Upload Documents to be authentication. </b>
                            </div>
                        </div>


                        <div class="form-group" id="fileInputContainer" style="display: none;">
                            <label for="fileInput">Upload File:</label>
                            If your request requires any supporting requirements, you can include them along with
                            a valid ID or student ID in a PDF file.
                            <input type="file" class="form-control-file" id="fileInput" name="fileInput">
                        </div>

                        <!-- Price display -->
                        <div class="form-group" id="priceContainer" style="display: none;">
                            <label for="price">Price:</label>
                            <input type="text" class="form-control" id="price" name="price" readonly>
                        </div>

                        <button type="button" id="confirmRegistrations" class="btn btn-primary">Submit Request</button>


                        <button type="button" id="add_request" class="btn btn-info" style="display: none">Add
                            Request</button>

                    </form>
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
    <?php include "../js/requestselect.php"; ?>
    <?php include "../php/student/notification.php"; ?>


</body>

</html>



<?php
} else {

    header("location: ../signin");
    exit();
}

?>