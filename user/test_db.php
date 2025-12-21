<?php
require_once "db.php";
$res = $conn->query("SELECT DATABASE() AS db, VERSION() AS ver");
$row = $res->fetch_assoc();
echo "Connected DB: " . $row["db"] . "<br>";
echo "MySQL/MariaDB Version: " . $row["ver"] . "<br>";
