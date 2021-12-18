<?php

require_once __DIR__ . '/response.php';
require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/function.php';



class CRUD
{
    private $db;

    public function __construct()
    {
        $this->db = db();
    }

    private function _getBinders(array $params = array()): array
    {
        $bind = '';
        foreach ($params as $param) {
            $type = gettype($param);
            if ($type == 'double') $bind .= 'd';
            else if ($type == 'integer') $bind .= 'd';
            else $bind .= 's';
        }
        $a_params[] = $bind;
        for ($i = 0; $i < count($params); $i++) {
            $a_params[] = $params[$i];
        }
        return $a_params;
    }

    public function select(string $query, array $params = array()): array
    {
        try {
            $stmt = $this->db->prepare($query);
            if (count($params) > 0) {
                $temp = $this->_getBinders($params);
                $binder = array();
                for ($i = 0; $i < count($temp); $i++) {
                    $binder[] = &$temp[$i];
                }
                call_user_func_array(array($stmt, 'bind_param'), $binder);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        } catch (\Throwable $th) {
            err($th->getMessage());
        } catch (\Error $e) {
            err($e->getMessage());
        } catch (\Exception $e) {
            err($e->getMessage());
        }
        return $data;
    }

    public function insert(string $query, array $params = array()): int
    {
        $insertId = 0;
        try {
            $stmt = $this->db->prepare($query);
            if (count($params) > 0) {
                $temp = $this->_getBinders($params);
                $binder = array();
                for ($i = 0; $i < count($temp); $i++) {
                    $binder[] = &$temp[$i];
                }
                call_user_func_array(array($stmt, 'bind_param'), $binder);
            }
            $stmt->execute();
            $insertId = $stmt->insert_id;
            $stmt->close();
        } catch (\Throwable $th) {
            err($th->getMessage());
        } catch (\Error $e) {
            err($e->getMessage());
        } catch (\Exception $e) {
            err($e->getMessage());
        }
        return  $insertId;
    }

    public function inserts(string $query, array $params = array()): int
    {
        $insertId = 0;
        try {
            $stmt = $this->db->prepare($query);
            if (count($params) > 0) {
                $temp = $this->_getBinders($params);
                $binder = array();
                for ($i = 0; $i < count($temp); $i++) {
                    $binder[] = &$temp[$i];
                }
                call_user_func_array(array($stmt, 'bind_param'), $binder);
            }
            $stmt->execute();
            $insertId = $stmt->affected_rows;
            $stmt->close();
        } catch (\Throwable $th) {
            err($th->getMessage());
        } catch (\Error $e) {
            err($e->getMessage());
        } catch (\Exception $e) {
            err($e->getMessage());
        }
        return  $insertId;
    }

    public function update(string $query, array $params = array()): int
    {
        $affectedRows = 0;
        try {
            $stmt = $this->db->prepare($query);
            if (count($params) > 0) {
                $temp = $this->_getBinders($params);
                $binder = array();
                for ($i = 0; $i < count($temp); $i++) {
                    $binder[] = &$temp[$i];
                }
                call_user_func_array(array($stmt, 'bind_param'), $binder);
            }
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
        } catch (\Throwable $th) {
            err($th->getMessage());
        } catch (\Error $e) {
            err($e->getMessage());
        } catch (\Exception $e) {
            err($e->getMessage());
        }
        return $affectedRows;
    }

    public function __destruct()
    {
        if ($this->db != null) {
            $this->db->close();
        }
    }
}
