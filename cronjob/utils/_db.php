<?php
require_once __DIR__ . '/response.php';
require_once __DIR__ . '../../../config.php';

function db(): \mysqli
{
    try {
        $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return $db;
    } catch (\Throwable $th) {
        err('Connection error');
    } catch (\Error $e) {
        err($e->getMessage());
    } catch (\Exception $e) {
        err($e->getMessage());
    }
}