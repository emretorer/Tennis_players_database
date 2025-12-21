<?php
require_once "../user/mongo.php";

$query = new MongoDB\Driver\Query([
    "status" => true
]);

$cursor = $manager->executeQuery("cs306.tickets", $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h2>Admin Ticket List</h2>

    <?php foreach ($cursor as $ticket): ?>
        <div style="border:1px solid red; padding:10px; margin:10px;">
            <b>User:</b> <?= $ticket->username ?><br>
            <b>Message:</b> <?= $ticket->message ?><br>
        </div>
    <?php endforeach; ?>
</body>
</html>
