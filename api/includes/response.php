<?php

function check($query, $err = '', $html = true)
{
    if (is_array($query))
        foreach ($query as $q)
            _checkPostValue($q, $err, $html);
    else
        _checkPostValue($query, $err, $html);
}

function _checkPostValue(string $q, $err, $html )
{
    if (!isset($_POST[$q])) {
        if ($err == '')
            $err = str_replace('_', ' ', ucwords($q, '_')) . ' required';
        err($err, 400);
    }
    $_POST[$q] = test_input($_POST[$q], $html);
}

function test_input($data, $html)
{
	$data = trim($data);
	$data = stripslashes($data);
	if ($html) {
		$data = htmlspecialchars($data);
	}
	return $data;
}

function err(string $err, int $code = 400): void
{
    $res = array();
    $res['ok'] = false;
    $res['err'] = $err;
    
    _finish($res);
}

function complete($data): void
{
    $res = array();
    $res['ok'] = true;
    $res['data'] = $data;
    _finish($res);
}

function _finish(array $res): void
{
    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60)));
    header('Pragma: no-cache');
    header('Content-Type: application/json');
    echo json_encode($res, JSON_PRETTY_PRINT);
    die();
}
