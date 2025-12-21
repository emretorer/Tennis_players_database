<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "Tennis_DB";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("MySQL connection failed: " . mysqli_connect_error());
}
?>
