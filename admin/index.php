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
    <div style="border:1px solid blue; padding:10px; margin:10px;">
        <b>User:</b> <?= htmlspecialchars($ticket->username) ?><br>
        <b>Message:</b> <?= htmlspecialchars($ticket->message) ?><br>
        <b>Created:</b> <?= htmlspecialchars($ticket->created_at) ?><br>

        <form method="post" action="update_status.php" style="margin-top:10px;">
            <input type="hidden" name="ticket_id" value="<?= (string)$ticket->_id ?>">

            <select name="status">
                <option value="1" <?= $ticket->status ? "selected" : "" ?>>Active</option>
                <option value="0" <?= !$ticket->status ? "selected" : "" ?>>Resolved</option>
            </select>

            <button type="submit">Update</button>
        </form>

        <!-- ✅ DOĞRU LINK -->
        <div style="margin-top:8px;">
    <a href="ticket_detail.php?id=<?= (string)$ticket->_id ?>">View Details</a>
</div>

    </div>
<?php endforeach; ?>

</body>
</html>
