<?php
// Include the necessary files
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/../../config.php'; // Adjusted the path for correctness

/**
 * Establishes a connection to the database and returns the mysqli object.
 * Utilizes defined constants from the configuration for the connection parameters.
 *
 * @return \mysqli The database connection object.
 */
function db(): \mysqli {
    // Attempt to establish a connection to the database
    try {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

        // Check for connection errors
        if ($db->connect_errno) {
            // If there is a connection error, use the err function to handle it
            err('Connection error: ' . $db->connect_error);
        }

        return $db;
    } catch (\Throwable $th) {
        // Handle any throwable errors during the connection
        err('Database connection error: ' . $th->getMessage());
    } catch (\Exception $e) {
        // Catch any generic exceptions
        err('Exception during database connection: ' . $e->getMessage());
    }
}
