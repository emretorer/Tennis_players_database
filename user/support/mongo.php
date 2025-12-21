<?php

try {
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
} catch (Exception $e) {
    die("MongoDB connection failed");
}
