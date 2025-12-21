<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../user/support/mongo.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $ticketId = $_POST["ticket_id"];
    $status   = ($_POST["status"] == "1") ? true : false;

    $manager = getMongoManager();

    $bulk = new MongoDB\Driver\BulkWrite();

    $bulk->update(
        ['_id' => new MongoDB\BSON\ObjectId($ticketId)],
        ['$set' => ['status' => $status]]
    );

    $manager->executeBulkWrite("cs306.tickets", $bulk);
}

header("Location: index.php");
exit;
