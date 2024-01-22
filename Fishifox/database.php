<?php
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "login_user";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


