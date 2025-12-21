<?php
function getMongoManager() {
    try {
        return new MongoDB\Driver\Manager(
            "mongodb://127.0.0.1:27017/?directConnection=true"
        );
    } catch (Exception $e) {
        die("MongoDB connection failed: " . $e->getMessage());
    }
}
