<?php

function check($query, $err, $emt = false, $html = true)
{
	if (!isset($_POST[$query])) {
		err($err, false);
	}
	if (!$emt && empty($_POST[$query])) {
		err($err . ' (Empty field !)', false);
	}
	$_POST[$query] = test_input($_POST[$query], $html);
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
