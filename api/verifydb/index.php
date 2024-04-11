<?php

/**
 * A script to establish a connection to the database.
 * It checks for the existence of a configuration file and attempts to connect to the database.
 * On success, it exits with code 0, and on failure, it exits with code 1.
 */

// Define the path to the configuration file.
$configPath = __DIR__ . "/../../config.php";

// Check if the configuration file exists. If not, exit with code 0.
if (!file_exists($configPath)) {
    exit(0);
}

// Include the configuration file.
require $configPath;

// Create a new mysqli object to establish a database connection.
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if the connection was successful.
if ($mysqli->connect_errno) {
    // If there is a connection error, exit with code 1.
    exit(1);
}

// If the connection is successful, exit with code 0.
exit(0);
