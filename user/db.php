<?php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";           // XAMPP default genelde boş
$DB_NAME = "tennis_db";
$DB_PORT = 3307;         // phpMyAdmin üstte 127.0.0.1:3307 gösteriyor

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($conn->connect_error) {
  die("MySQL connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
