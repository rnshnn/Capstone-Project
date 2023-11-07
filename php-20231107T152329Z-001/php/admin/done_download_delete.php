<?php
include "../dbcon.php";
$output = '';
if (isset($_POST["click"]) && $_POST["click"] === "2") {
    $query = "SELECT * FROM done";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $title = "ALL-RELEASED-DOCUMENT-REQUEST";
        $date = date('Y-m-d');
        $output .= '
        <table style="border: 1px solid black; ">
            <tr>
                <td style="background-color: #17A2b8;" colspan="17"><h2>'.$title.'</h2></td>
            </tr>
            <tr>
                <td colspan="2"><h4>Date: '.$date.'</h4></td>
                <td colspan="5" style="color:red;"> This Request is copy from all decline documents of PHINMA AU:</td>
            </tr>
            <tr>
                <th style="background-color:#FFC107; border:1px solid gray;">No.</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Name</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Course</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Year</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Student ID</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Assigned by:</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Releaser</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Request</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Copy</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Price</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Date</th>
                <th style="background-color:#FFC107; border:1px solid gray;">Reference No.</th>
                <th colspan="5" style="background-color:#FFC107; border:1px solid gray;">Action</th>
            </tr>
        ';
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {
            $output .= '
                <tr>
                    <td style="text-align: center; border:1px solid gray;">' . $i++ . '</td>
                    <td style="text-align: left; border:1px solid gray;">' . $row["D_FULL"] . '</td>
                    <td style="text-align: left; border:1px solid gray;" >' . $row["D_COU"] . '</td>
                    <td style="text-align: left; border:1px solid gray;" >' . $row["D_YEAR"] . '</td>
                    <td style="text-align: left; border:1px solid gray;" >' . $row["D_SID"] . '</td>
                    <td style="color:darkgreen; text-align: left; border:1px solid gray;">' . $row["D_ASIG"] . '</td>
                    <td style="color:darkgreen; text-align: left; border:1px solid gray;">' . $row["D_REA"] . '</td>
                    <td style="text-align: left; border:1px solid gray;">' . $row["D_documentType"] . ' <br> ' . $row["D_documentType_2"] . ' <br> ' . $row["D_documentType_3"] . '</td>
                    <td style="text-align: center; border:1px solid gray;">' . $row["D_numCopies"] . ' <br> ' . $row["D_numCopies_2"] . ' <br> ' . $row["D_numCopies_3"] . '</td>
                    <td style="text-align: center; border:1px solid gray;">' . $row["D_price"] . '</td>
                    <td style="text-align: center; border:1px solid gray;">' . $row["D_DATE"] . '</td>
                    <td style="color:darkgreen; text-align: center; border:1px solid gray;">' . $row["D_CODE"] . '</td>
                    <td  colspan="5" style="color:orange; text-align: left; border:1px solid gray;">' . $row["S_MES"] . '</td>
                </tr>
            ';
        }
        $output .= '</table>';

        // Delete all data from the decline table
        $deleteQuery = "DELETE FROM done";
        mysqli_query($conn, $deleteQuery);

        // Generate the Excel file
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename=All-Released-Document-request.xls');
        echo $output;
        exit;
    }
}
?>