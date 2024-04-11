<?php

/**
 * Validates the presence of required fields in the POST request and sanitizes them.
 *
 * @param mixed  $query The field or array of fields to be checked in the POST request.
 * @param string $err Custom error message, if any.
 * @param bool   $html Specifies whether to convert special characters to HTML entities.
 */
function check($query, string $err = '', bool $html = true): void {
    if (is_array($query)) {
        foreach ($query as $q) {
            _checkPostValue($q, $err, $html);
        }
    } else {
        _checkPostValue($query, $err, $html);
    }
}

/**
 * Checks for a specific POST field and sanitizes it.
 *
 * @param string $q The POST field to check.
 * @param string $err Custom error message, if any.
 * @param bool   $html Specifies whether to convert special characters to HTML entities.
 */
function _checkPostValue(string $q, string $err, bool $html): void {
    if (!isset($_POST[$q]) || $_POST[$q] === '') {
        if (empty($err)) {
            $err = str_replace('_', ' ', ucwords($q, '_')) . ' is required.';
        }
        err($err, 400);
    }
    $_POST[$q] = test_input($_POST[$q], $html);
}

/**
 * Sanitizes a given input.
 *
 * @param mixed $data The data to be sanitized.
 * @param bool  $html Specifies whether to convert special characters to HTML entities.
 * @return mixed The sanitized data.
 */
function test_input($data, bool $html) {
    $data = trim($data);
    $data = stripslashes($data);
    if ($html) {
        $data = htmlspecialchars($data);
    }
    return $data;
}

/**
 * Sends an error response and terminates the script.
 *
 * @param string $err The error message.
 * @param int    $code The HTTP status code to send.
 */
function err(string $err, int $code = 400): void {
    http_response_code($code);
    $res = ['ok' => false, 'err' => $err];
    _finish($res);
}

/**
 * Sends a success response and terminates the script.
 *
 * @param mixed $data The data to be sent in the response.
 */
function complete($data): void {
    $res = ['ok' => true, 'data' => $data];
    _finish($res);
}

/**
 * Outputs the response in JSON format and terminates the script.
 *
 * @param array $res The response data to output.
 */
function _finish(array $res): void {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60)));
    header('Pragma: no-cache');
    header('Content-Type: application/json');
    echo json_encode($res, JSON_PRETTY_PRINT);
    exit();  // Using exit() for clarity
}
