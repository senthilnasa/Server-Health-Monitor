<?php

require_once __DIR__ . '/response.php';
require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/function.php';

// Enforce the use of POST method for requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    err("Invalid Request");
}

// Start the session with a custom session name if not already started
if (!isset($_SESSION)) {
    session_name('senthilnasa');
    session_start();
}

/**
 * CRUD class for handling database operations.
 */
class CRUD
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Generates a string of types for bind_param function based on the parameters provided.
     *
     * @param array $params Parameters for SQL query.
     * @return array Array containing types and the parameters.
     */
    private function _getBinders(array $params): array
    {
        $bind = '';
        foreach ($params as $param) {
            $bind .= gettype($param) == 'double' || gettype($param) == 'integer' ? 'd' : 's';
        }

        return array_merge([$bind], $params);
    }

    /**
     * Executes a SELECT query with optional parameters.
     *
     * @param string $query SQL query.
     * @param array $params Parameters to bind to the query.
     * @return array Result set.
     */
    public function select(string $query, array $params = []): array
    {
        $data = [];
        try {
            $stmt = $this->db->prepare($query);
            if ($params) {
                $stmt->bind_param(...$this->_getBinders($params));
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (\Throwable $th) {
            err($th->getMessage());
        }

        return $data;
    }

    /**
     * Executes an INSERT query and returns the inserted ID.
     *
     * @param string $query SQL query.
     * @param array $params Parameters for the query.
     * @return int Last inserted ID.
     */
    public function insert(string $query, array $params = []): int
    {
        return $this->_executeWriteQuery($query, $params);
    }

    /**
     * Executes an INSERT/UPDATE/DELETE query and returns the number of affected rows.
     *
     * @param string $query SQL query.
     * @param array $params Parameters for the query.
     * @return int Number of rows affected.
     */
    public function update(string $query, array $params = []): int
    {
        return $this->_executeWriteQuery($query, $params);
    }

    /**
     * A private method to handle INSERT, UPDATE, DELETE operations.
     *
     * @param string $query SQL query.
     * @param array $params Parameters for the query.
     * @return int The last inserted ID or the number of affected rows.
     */
    private function _executeWriteQuery(string $query, array $params): int
    {
        $result = 0;
        try {
            $stmt = $this->db->prepare($query);
            if ($params) {
                $stmt->bind_param(...$this->_getBinders($params));
            }
            $stmt->execute();
            $result = $stmt->affected_rows;
            $stmt->close();
        } catch (\Throwable $th) {
            err($th->getMessage());
        }

        return $result;
    }

    public function __destruct()
    {
        $this->db->close();
    }
}


