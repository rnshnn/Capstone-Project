<?php include_once ("controller.php"); ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/signup.css">
    <title>RMS - Verify</title>
</head>

<body>

    <div class="center-container">
        <form class="custom-form" method="post" action="verifyEmail">

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

            <div class="header">
                <img src="../../img/favicon - Copy.png" alt="Logo" class="logo">
                <div class="title">REGISTRAR MANAGEMENT SYSTEM</div>
            </div>

            <div class="form-outline">
                <label class="form-label" for="form2Example2">Verification code</label>
                <input type="number" id="form2Example2" name="R_CODE" class="form-control"
                    placeholder="Enter your code" />
            </div>

            <button type="submit" name="verify" class="btn btn-primary btn-block">Verify</button>
        </form>

    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/sweetalert.min.js"></script>
    <script src="../../js/sweetalert2.all.min.js"></script>
    <script src="../../js/registerinput.js"></script>



</body>

</html>