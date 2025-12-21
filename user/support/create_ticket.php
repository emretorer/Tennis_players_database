<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "mongo.php";

$created = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $message  = trim($_POST["message"] ?? "");

    if ($username !== "" && $message !== "") {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->insert([
            "username"   => $username,
            "message"    => $message,
            "created_at" => date("Y-m-d H:i:s"),
            "status"     => true,
            "comments"   => []
        ]);

        $manager = getMongoManager();
        $manager->executeBulkWrite("cs306.tickets", $bulk);

        // ✅ Ticket oluşturuldu: artık form göstermeyeceğiz
        $created = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Create a Ticket</title>
</head>
<body>

<?php if ($created): ?>
    <h2>Ticket created successfully 🎉</h2>

    <p>
        <a href="create_ticket.php">Back to Create Ticket</a><br><br>
        <a href="ticket_list.php">Go to Ticket List</a>
    </p>

<?php else: ?>

    <!-- ÜST LİNKLER -->
    <p>
        <a href="ticket_list.php">View Tickets</a><br>
        <a href="/Tennis_players_database/user">Home</a>
    </p>

    <h2>Create a Ticket</h2>

    <form method="post">
        <input type="text" name="username" placeholder="username" required><br><br>

        <textarea name="message" placeholder="message" required></textarea><br><br>

        <button type="submit">Create Ticket</button>
    </form>

<?php endif; ?>

</body>
</html>
