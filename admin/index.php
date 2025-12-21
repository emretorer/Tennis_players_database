<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../user/support/mongo.php";

$manager = getMongoManager();

$query = new MongoDB\Driver\Query([
    //"status" => true
]);

$cursor = $manager->executeQuery("cs306.tickets", $query);
?>
