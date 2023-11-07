<?php
$server_name="localhost";
$username="root";
$password="";
$database_name="rms";

$conn=mysqli_connect($server_name,$username,$password,$database_name);

if(!$conn)
{
    die("Connection Failed:" . mysqli_connect_error());
}
?>