<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "mongo.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $message  = $_POST["message"];

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

    // ✅ PDF’teki gibi: ticket oluşturunca listeye dön
    header("Location: view_tickets.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create a Ticket</title>
</head>
<body>

<!-- ÜST LİNKLER -->
<p>
    <a href="view_tickets.php">View Tickets</a><br>
    <a href="/Tennis_players_database/user">Home</a>

</p>

<h2>Create a Ticket</h2>

<form method="post">
    <input type="text" name="username" placeholder="username" required><br><br>

    <textarea name="message" placeholder="message" required></textarea><br><br>

    <button type="submit">Create Ticket</button>
</form>
</body>
</html>
