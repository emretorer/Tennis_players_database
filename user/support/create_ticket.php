<?php
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

    $manager->executeBulkWrite("cs306.tickets", $bulk);

    echo "Ticket created successfully 🎉<br>";
    echo '<a href="index.php">Back</a>';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Ticket</title>
</head>
<body>
    <h2>Create Support Ticket</h2>

    <form method="post">
        Username:<br>
        <input type="text" name="username" required><br><br>

        Message:<br>
        <textarea name="message" required></textarea><br><br>

        <button type="submit">Create</button>
    </form>

    <br>
    <a href="index.php">Back</a>
</body>
</html>
