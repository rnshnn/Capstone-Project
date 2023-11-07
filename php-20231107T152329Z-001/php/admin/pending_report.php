<?php
require_once '../dbcon.php';
$output = '';
if (isset($_POST["btn_clicked"]) && $_POST["btn_clicked"] === "1") {
    $query = "SELECT S_ASIG,
        SUM(CASE WHEN (documentType = 'TOR for board exam' OR documentType_2 = 'TOR for board exam' OR documentType_3 = 'TOR for board exam') THEN numCopies ELSE 0 END) AS TOR,
        SUM(CASE WHEN (documentType = 'TOR for reference' OR documentType_2 = 'TOR for reference' OR documentType_3 = 'TOR for reference') THEN numCopies ELSE 0 END) AS TOR1,
        SUM(CASE WHEN (documentType = 'TOR for copy school' OR documentType_2 = 'TOR for copy school' OR documentType_3 = 'TOR for copy school') THEN numCopies ELSE 0 END) AS TOR2,
        SUM(CASE WHEN (documentType = 'Certificate of Unit Earned' OR documentType_2 = 'Certificate of Unit Earned' OR documentType_3 = 'Certificate of Unit Earned') THEN numCopies ELSE 0 END) AS CU,
        SUM(CASE WHEN (documentType = 'Certificate of Enrollment' OR documentType_2 = 'Certificate of Enrollment' OR documentType_3 = 'Certificate of Enrollment') THEN numCopies ELSE 0 END) AS CER,
        SUM(CASE WHEN (documentType = 'Certificate of Graduation' OR documentType_2 = 'Certificate of Graduation' OR documentType_3 = 'Certificate of Graduation') THEN numCopies ELSE 0 END) AS ER,
        SUM(CASE WHEN (documentType = 'Certificate of Grades' OR documentType_2 = 'Certificate of Grades' OR documentType_3 = 'Certificate of Grades') THEN numCopies ELSE 0 END) AS TER,
        SUM(CASE WHEN (documentType = 'Certificate of Enrolled Semester' OR documentType_2 = 'Certificate of Enrolled Semester' OR documentType_3 = 'Certificate of Enrolled Semester') THEN numCopies ELSE 0 END) AS MER,
        SUM(CASE WHEN (documentType = 'Certificate of English Proficiency' OR documentType_2 = 'Certificate of English Proficiency' OR documentType_3 = 'Certificate of English Proficiency') THEN numCopies ELSE 0 END) AS SER,
        SUM(CASE WHEN (documentType = 'Certificate of NSTP Serial Number' OR documentType_2 = 'Certificate of NSTP Serial Number' OR documentType_3 = 'Certificate of NSTP Serial Number') THEN numCopies ELSE 0 END) AS SOR,
        SUM(CASE WHEN (documentType = 'Certificate of Honor' OR documentType_2 = 'Certificate of Honor' OR documentType_3 = 'Certificate of Honor') THEN numCopies ELSE 0 END) AS MOR,
        SUM(CASE WHEN (documentType = 'Subject Description' OR documentType_2 = 'Subject Description' OR documentType_3 = 'Subject Description') THEN numCopies ELSE 0 END) AS SUB,
        SUM(CASE WHEN (documentType = 'Authentication' OR documentType_2 = 'Authentication' OR documentType_3 = 'Authentication') THEN numCopies ELSE 0 END) AS AUT,
        SUM(CASE WHEN (documentType = 'CAV' OR documentType_2 = 'CAV' OR documentType_3 = 'CAV') THEN numCopies ELSE 0 END) AS CAV,
        SUM(CASE WHEN (documentType = 'HONOROBALE DISMISSAL' OR documentType_2 = 'HONOROBALE DISMISSAL' OR documentType_3 = 'HONOROBALE DISMISSAL') THEN numCopies ELSE 0 END) AS HD,
        SUM(CASE WHEN (documentType = 'FORM - 137' OR documentType_2 = 'FORM - 137' OR documentType_3 = 'FORM - 137') THEN numCopies ELSE 0 END) AS CD,
        SUM(CASE WHEN (documentType = 'diploma' OR documentType_2 = 'diploma' OR documentType_3 = 'diploma') THEN numCopies ELSE 0 END) AS Diploma,
        SUM(numCopies + numCopies_2 + numCopies_3) AS TOTAL_COPIES
        FROM request
        WHERE documentType IN ('TOR For Board Exam', 'FORM - 137', 'CAV', 'HONOROBALE DISMISSAL', 'TOR For Reference', 'Subject Description', 'Authentication', 'Certificate of Honor', 'Certificate of NSTP Serial Number', 'Certificate of English Proficiency', 'TOR For Copy School', 'Certificate of Enrolled Semester', 'Certificate of Graduation', 'Certificate of Unit Earned', 'Certificate of Grades', 'diploma', 'Certificate of Enrollment')
        GROUP BY S_ASIG";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $title = "GENERATE-PENDING-DOCUMENT-REQUEST";
            $date = date('Y-m-d');

            // Assuming that the timestamp of the last download is stored in the $last_download variable
            $S_STUN = time(); // or retrieve it from a database column

            // Convert the timestamps to just date strings
            $currentDateStr = date('Y-m-d');
            $lastDownloadDateStr = date('Y-m-d', $S_STUN);

            // Check if the last download was made today by comparing the date part
            if ($lastDownloadDateStr === $currentDateStr) {
                $elapsed_time_str = 'today';
            } else {
                // Calculate the time elapsed since the last download
                $elapsed_time = time() - $S_STUN;

                // Format the elapsed time as a human-readable string
                if ($elapsed_time < 60) {
                    $elapsed_time_str = $elapsed_time . ' seconds ago';
                } elseif ($elapsed_time < 3600) {
                    $elapsed_time_str = floor($elapsed_time / 60) . ' minutes ago';
                } else {
                    $elapsed_time_str = floor($elapsed_time / 3600) . ' hours ago';
                }
            }
                // ... the rest of your code for generating the report ...
            
            
                $output .= '
                <table style="border: 1px solid black; ">
                    <tr>
                        <td style="color: white; background-color:#343A40;" colspan="20"><h2>'.$title.'</h2></td>
                    </tr>
                    <tr>
                    <td colspan="1"><h4>Last Generate: '.$lastDownloadDateStr.'</h4></td>
                        <td colspan="2"><h4>Date: '.$date.'</h4></td>
                        <td colspan="5" style="color:red;"> This Request is copy from all request documents of PHINMA AU:</td>
                    </tr>
                    <tr >
                        <th style="background-color:#FFC107; border:1px solid gray;" colspan="2">Name</th>
                        <th style="background-color:#FFC107; border:1px solid gray;">Total Copies</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">TOR For Board Exam</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">TOR For Reference</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">TOR For Copy School</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">Certificate of Unit Earned</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">Certificate of Enrollment</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">Certificate of Graduation</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">Certificate of Grades</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">Certificate of Enrolled Semester</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">Certificate of English Proficiency</th>
                        <th style="background-color:#FFC107;  border:1px solid gray;">Certificate of NSTP Serial Number</th>
                        <th style="background-color:#FFC107; border:1px solid gray;">Certificate of Honor</th>
                        <th style="background-color:#FFC107; border:1px solid gray;">Subject Description</th>
                        <th style="background-color:#FFC107; border:1px solid gray;">Authentication</th>
                        <th style="background-color:#FFC107; border:1px solid gray;">CAV</th>
                        <th style="background-color:#FFC107; border:1px solid gray;">HD</th>
                        <th style="background-color:#FFC107; border:1px solid gray;">Form 1-37</th>
                        <th style="background-color:#FFC107; border:1px solid gray;">Diploma</th>
                    </tr>
                    ';
                    $tor_total = 0;
                    $tor1_total = 0;
                    $tor2_total = 0;
                    $cu_total = 0;
                    $CER_total = 0;
                    $ER_total = 0;
                    $ter_total = 0;
                    $mer_total = 0;
                    $SER_total = 0;
                    $sor_total = 0;
                    $mor_total = 0;
                    $SUB_total = 0;
                    $AUT_total = 0;
                    $cav_total = 0;
                    $hd_total = 0;
                    $cd_total = 0;
                    $diploma_total = 0;
                    $copies_total = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $tor_total += $row["TOR"];
                        $tor1_total += $row["TOR1"];
                        $tor2_total += $row["TOR2"];
                        $cu_total += $row["CU"];
                        $CER_total += $row["CER"];
                        $ER_total += $row["ER"];
                        $ter_total += $row["TER"];
                        $mer_total += $row["MER"];
                        $SER_total += $row["SER"];
                        $sor_total += $row["SOR"];
                        $mor_total += $row["MOR"];
                        $SUB_total += $row["SUB"];
                        $AUT_total += $row["AUT"];
                        $cav_total += $row["CAV"];
                        $hd_total += $row["HD"];
                        $cd_total += $row["CD"];
                        $diploma_total += $row["Diploma"];
                        $copies_total += $row["TOTAL_COPIES"];
                        $output .= '
                        <tr text-align: center;>
                            <td style="border:1px solid gray;" colspan="2">' . $row["S_ASIG"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["TOTAL_COPIES"] . '</td>
                            <td style="text-align: center;  border:1px solid gray;">' . $row["TOR"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["TOR1"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["TOR2"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["CU"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["CER"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["ER"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["TER"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["MER"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["SER"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["SOR"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["MOR"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["SUB"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["AUT"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["CAV"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["HD"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["CD"] . '</td>
                            <td style="text-align: center; border:1px solid gray;">' . $row["Diploma"] . '</td>
                        </tr>
                        ';
                    }
                    $output .= '
                        <tr text-align: center;>
                            <td style="background:lightgreen; border:1px solid gray;" colspan="2"><strong>Total:</strong></td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $copies_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $tor_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $tor1_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $tor2_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $cu_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $CER_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $ER_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $ter_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $mer_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $SER_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $sor_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $mor_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $SUB_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $AUT_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $cav_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $hd_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $cd_total . '</td>
                            <td style="background:lightgreen; text-align: center; border:1px solid gray;">' . $diploma_total . '</td>
                        </tr>
                            ';

                        $output .= '
                        <tr>
                            <td><strong></strong></td>
                        </tr>
                            ';

                            $output .= '
                            <tr text-align: center;>
                                <td colspan="2"><strong>Total Copies:</strong></td>
                                <td style="background:lightgreen;  text-align: center;">' . $copies_total . '</td>
                            </tr>
                                ';

                                $output .= '
                                <tr text-align: center;>
                                <td></td>
                                </tr>
                                    ';

                                    $output .= '
                                    <tr text-align: center;>
                                    <td colspan="5" style="color:red;">Pending refers to document request that are currently on hold due to grades concern such as INC,no grades,etc.</td>
                                    </tr>
                                        ';
                    }

                    $output .= '</table>';
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename=Generate-Pending-Document-request.xls');
                    echo $output;
                    exit;
                } else {
                    echo "No records found.";
                }
            } else {
                echo "Error: " . mysqli_error($conn); // Print the specific error message
            }
        
        ?>