<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
if (isset($_GET['id']) && isset($_GET['table'])) {
$notificationId = $_GET['id'];
$table = $_GET['table'];

$sql = "UPDATE $table SET S_STA_PAY = 'seen' WHERE ";

switch ($table) {
    case 'payment':
        $sql .= "P_ID = ?";
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
$sql = "SELECT * FROM $table WHERE S_STA_PAY = 'unseen' ";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
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

$payments = getNotifications($conn, 'payment');


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
                        <?php echo count($payments);?>
                    </span>
                    <div class="notification-popup" id="notificationPopup">
                        <?php

                        function renderNotifications($notifications, $table) {
                            foreach ($notifications as $notification) {
                                $statusClass = ($notification["S_STA_PAY"] == "seen") ? "read" : "";
                                $link = getNotificationLink($table);

                                $message = isset($notification["S_MES"]) ? $notification["S_MES"] : "";

                                $pannel = isset($notification["pannel"]) ? $notification["pannel"] : "";

                                $fullName = isset($notification["P_FULL"]) ? $notification["P_FULL"] : "";
                                
                                $CODEName = isset($notification["P_CODE"]) ? $notification["P_CODE"] : "";

                                $price = isset($notification["P_price"]) ? $notification["P_price"] : "";

                                $timestamp = isset($notification["P_STAMP"]) ? $notification["P_STAMP"] : ""; // Get the timestamp

                                // Convert the timestamp to a human-readable format
                                $formattedTimestamp = timeSince($timestamp);

                                $id = getNotificationId($notification, $table);
                                echo '<a href="' . $link . '" class="notification ' . $statusClass . '" data-id="' . $id . '" data-table="' . $table . '">' . htmlspecialchars($fullName . " : " .$message . " : " .$price) . "<br>". htmlspecialchars($pannel . ": Ref No. " . $CODEName) . '<br> (' .$formattedTimestamp. ')</a>';
                                }
                        }

                        function getNotificationId($notification, $table) {
                            switch ($table) {
                                case 'payment':
                                    return $notification["P_ID"];
                                default:
                                    return '';
                            }
                        }

                        function getNotificationLink($table) {
                            switch ($table) {
                                case 'payment':
                                    return 'http://localhost/newventure/accounting/payment';
                                default:
                                    return '#'; 
                            }
                        }


                        renderNotifications($payments, 'payment');
                   
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
        case 'payment':
            urls.push("payment.php", "account.php", "dashboard.php");
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
        return notification.S_STA_PAY === "unseen";
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