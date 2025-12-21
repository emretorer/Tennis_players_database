<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../user/support/mongo.php";

$manager = getMongoManager();

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
        <b>Status:</b> <?= $ticket->status ? "Open" : "Closed" ?><br>
        <b>Created:</b> <?= $ticket->created_at ?><br>
    </div>
<?php endforeach; ?>

</body>
</html>
