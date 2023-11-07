<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
if (isset($_GET['id']) && isset($_GET['table'])) {
$notificationId = $_GET['id'];
$table = $_GET['table'];

$sql = "UPDATE $table SET S_STA_REG = 'seen' WHERE ";

switch ($table) {
    case 'request':
        $sql .= "S_ID = ?";
        break;
    case 'payment':
        $sql .= "P_ID = ?";
        break;
    case 'process':
        $sql .= "O_ID = ?";
        break;
    case 'releasing':
        $sql .= "L_ID = ?";
        break;
    case 'done':
        $sql .= "D_ID = ?";
        break;
    default:
        $sql .= "S_ID = ?"; 
}// Modify this query as needed

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $notificationId); // Assuming S_ID is an integer
    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "Error updating notification: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing SQL statement: " . mysqli_error($conn);
}
}

function getNotifications($conn, $table) {
$userEmail = $_SESSION['R_FULL']; // Use the session variable
$emailField = getEmailField($table); // Get the correct email field based on the table

$sql = "SELECT * FROM $table WHERE S_STA_REG = 'unseen' AND $emailField = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $notifications = array();
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $notifications[] = $row; // Append the row to the notifications array
            }
            mysqli_free_result($result);
        }
        return $notifications;
    } else {
        echo "Error executing SQL statement: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing SQL statement: " . mysqli_error($conn);
}

return array(); // Return an empty array on error
}
function getEmailField($table) {
// Define the email field based on the table
switch ($table) {
    case 'request':
        return 'S_ASIG';
    case 'payment':
        return 'P_ASIG';
    case 'process':
        return 'O_ASIG';
    case 'releasing':
        return 'L_ASIG';
    case 'done':
        return 'D_ASIG';
    default:
        return 'S_ASIG'; // Default to S_EMAIL for unknown tables
}
}

function timeSince($timestamp) {
    $seconds = time() - strtotime($timestamp);

    if ($seconds < 60) {
        return'just now';
    } else {
        $minutes = floor($seconds / 60);
        if ($minutes < 60) {
            return'just now';
        } else {
            $hours = floor($minutes / 60);
            if ($hours < 24) {
                return'just now';
            } else {
                $days = floor($hours / 24);
                return'just now';
            }
        }
    }
}

$requests = getNotifications($conn, 'request');
$payments = getNotifications($conn, 'payment');
$processes = getNotifications($conn, 'process');
$releasings = getNotifications($conn, 'releasing');
$dones = getNotifications($conn, 'done');

// Close the connection
mysqli_close($conn);
?>

<div class="position-fixed" style="top: -33px; right: -5px; font-size:12px">
    <div class="container mt-5">
        <div class="row justify-content-end">
            <div class="col-auto">
                <div class="bell-icon">
                    <i class="fas fa-bell" aria-hidden="true"></i>
                    <span class="notification-count" id="notificationCount">
                        <?php echo count($requests) + count($payments) + count($processes) + count($releasings) + count($dones);?>
                    </span>
                    <div class="notification-popup" id="notificationPopup">
                        <?php

                        function renderNotifications($notifications, $table) {
                            foreach ($notifications as $notification) {
                                $statusClass = ($notification["S_STA_REG"] == "seen") ? "read" : "";
                                $link = getNotificationLink($table);

                                $message = isset($notification["S_MES"]) ? $notification["S_MES"] : "";

                                $pannel = isset($notification["pannel"]) ? $notification["pannel"] : "";

                                $request = isset($notification["documentType"]) ? $notification["documentType"] : "";
                                $request1 = isset($notification["S_documentType"]) ? $notification["S_documentType"] : "";
                                $request3 = isset($notification["O_documentType"]) ? $notification["O_documentType"] : "";
                                $request2 = isset($notification["L_documentType"]) ? $notification["L_documentType"] : "";
                                $request4 = isset($notification["D_documentType"]) ? $notification["D_documentType"] : "";

                                $fullName = isset($notification["S_FULL"]) ? $notification["S_FULL"] : "";
                                $fullName2 = isset($notification["P_FULL"]) ? $notification["P_FULL"] : "";
                                $fullName3 = isset($notification["O_FULL"]) ? $notification["O_FULL"] : "";
                                $fullName4 = isset($notification["L_FULL"]) ? $notification["L_FULL"] : "";
                                $fullName5 = isset($notification["D_FULL"]) ? $notification["D_FULL"] : "";
                                
                                $CODEName1 = isset($notification["S_CODE"]) ? $notification["S_CODE"] : "";
                                $CODEName2 = isset($notification["P_CODE"]) ? $notification["P_CODE"] : "";
                                $CODEName3 = isset($notification["O_CODE"]) ? $notification["O_CODE"] : "";
                                $CODEName4 = isset($notification["L_CODE"]) ? $notification["L_CODE"] : "";
                                $CODEName5 = isset($notification["D_CODE"]) ? $notification["D_CODE"] : "";

                                $price = isset($notification["P_price"]) ? $notification["P_price"] : "";
                                $price1 = isset($notification["C_price"]) ? $notification["C_price"] : "";

                                $timestamp = isset($notification["S_STUN"]) ? $notification["S_STUN"] : ""; // Get the timestamp

                                // Convert the timestamp to a human-readable format
                                $formattedTimestamp = timeSince($timestamp);

                                $id = getNotificationId($notification, $table);
                                echo '<a href="' . $link . '" class="notification ' . $statusClass . '" data-id="' . $id . '" data-table="' . $table . '">' . htmlspecialchars($fullName5 . " " .$fullName4 . " " .$fullName3 . " " .$fullName . " " . $fullName2 . " : " . $message . " : " . $request. " " .$request1. " " .$request2. " " .$request3. " " .$request4. " " .$price) . "<br>". htmlspecialchars($pannel . ": Ref No. " . $CODEName1 . " " . $CODEName2 . " " . $CODEName3 . " " . $CODEName4 . " " . $CODEName5) . '<br> (' .$formattedTimestamp. ')</a>';
                                }
                        }

                        function getNotificationId($notification, $table) {
                            switch ($table) {
                                case 'request':
                                    return $notification["S_ID"];
                                case 'payment':
                                    return $notification["P_ID"];
                                case 'process':
                                    return $notification["O_ID"];
                                case 'releasing':
                                    return $notification["L_ID"];
                                case 'done':
                                    return $notification["D_ID"];
                                default:
                                    return '';
                            }
                        }

                        function getNotificationLink($table) {
                            switch ($table) {
                                case 'request':
                                    return 'http://localhost/newventure/registrar/pending';
                                case 'payment':
                                    return 'http://localhost/newventure/registrar/payment';
                                case 'process':
                                    return 'http://localhost/newventure/registrar/onprocess';
                                case 'releasing':
                                    return 'http://localhost/newventure/registrar/releasing';
                                case 'done':
                                    return 'http://localhost/newventure/registrar/done';
                                default:
                                    return '#'; 
                            }
                        }

                        renderNotifications($requests, 'request');
                        renderNotifications($payments, 'payment');
                        renderNotifications($processes, 'process');
                        renderNotifications($releasings, 'releasing');
                        renderNotifications($dones, 'done');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
$('#notificationPopup').on('click', '.notification', function() {
    var notificationId = $(this).data('id');
    var table = $(this).data('table');
    markNotificationAsSeen(notificationId, table);
});

function markNotificationAsSeen(id, table) {
    var urls = []; // Define an array for URLs

    switch (table) {
        case 'request':
            urls.push("pending.php", "process.php", "releasing.php", "done.php",
                "dashboard.php");
            break;
        case 'payment':
            urls.push("pending.php", "process.php", "releasing.php", "done.php",
                "dashboard.php");
            break;
        case 'process':
            urls.push("pending.php", "process.php", "releasing.php", "done.php",
                "dashboard.php");
            break;
        case 'releasing':
            urls.push("pending.php", "process.php", "releasing.php", "done.php",
                "dashboard.php");
            break;
        case 'done':
            urls.push("pending.php", "process.php", "releasing.php", "done.php",
                "dashboard.php");
            break;
        default:
            urls.push("#");
    }

    // Loop through the URLs
    for (var i = 0; i < urls.length; i++) {
        $.get(urls[i], {
            id: id,
            table: table // Send the table name in the request
        }, function(data) {
            console.log('AJAX response:', data); // Log the response
            if (data === "success") {
                updateNotificationCount();
                // Update the status class in the HTML
                $('.notification[data-id="' + id + '"]').removeClass("unseen").addClass("read");
            } else {
                // Handle error here
                console.error("Error marking notification as seen");
            }
        });
    }
}


function updateNotificationCount() {
    var unseenCount = notifications.filter(function(notification) {
        return notification.S_STA_REG === "unseen";
    }).length;

    $('#notificationCount').text(unseenCount);

    if (unseenCount === 0) {
        $('#notificationCount').hide();
    } else {
        $('#notificationCount').show();
    }
}

$('#notificationPopup').on('click', '.notification', function() {
    var notificationId = $(this).data('id');
    var table = $(this).data('table');
    markNotificationAsSeen(notificationId, table);
});

$('.bell-icon').click(function(e) {
    $('#notificationPopup').toggle();
});
</script>