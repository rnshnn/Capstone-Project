<?php
session_start();
require_once '../dbcon.php';

if (isset($_POST['Download_P_PROOF'])) {
    $file_name = $_POST['P_PROOF'];
    $file_path = "../../upload/" . $file_name;

    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        header('Location: ../../accounting/payment?error=File not found for download');
        exit();
    }
}