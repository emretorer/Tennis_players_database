<?php
require_once "mongo.php";

$query = new MongoDB\Driver\Query([
    "status" => true
]);

$cursor = $manager->executeQuery("cs306.tickets", $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Tickets</title>
</head>
<body>
    <h2>Active Tickets</h2>

    <?php foreach ($cursor as $ticket): ?>
        <div style="border:1px solid #000; padding:10px; margin:10px;">
            <b>User:</b> <?= $ticket->username ?><br>
            <b>Message:</b> <?= $ticket->message ?><br>
            <b>Created:</b> <?= $ticket->created_at ?><br>
        </div>
    <?php endforeach; ?>

    <a href="index.php">Back</a>
</body>
</html>
