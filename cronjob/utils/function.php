<?php

// Define a function to perform a cURL operation to check website availability and performance
function website_check($db, $href, $header = false, $body = true, $timeout = null, $add_agent = true, $website_username = false, $website_password = false, $request_method = null, $post_field = null, $server_id, $redirect, $ssl) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => preg_replace('/(.*)(%cachebuster%)/', '$1' . time(), $href),
        CURLOPT_HEADER => $header,
        CURLOPT_NOBODY => !$body,
        CURLOPT_FOLLOWLOCATION => $redirect,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => $ssl ? 2 : 0,
        CURLOPT_SSL_VERIFYPEER => $ssl,
        CURLOPT_CONNECTTIMEOUT => $timeout,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_ENCODING => '',
        CURLOPT_CERTINFO => $ssl,
        CURLOPT_USERAGENT => $add_agent ? 'Mozilla/5.0 (compatible; CustomBot/1.0)' : null,
    ]);

    if ($website_username && $website_password) {
        curl_setopt($ch, CURLOPT_USERPWD, "$website_username:$website_password");
    }

    if ($request_method) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_method);
    }

    if ($post_field) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
    }

    curl_exec($ch);
    $result = curl_getinfo($ch);
    curl_close($ch);

    return handleCurlResult($db, $result, $server_id, $ssl);
}

function handleCurlResult($db, $result, $server_id, $ssl) {
    $error = '';

    if (!empty($result['redirect_url']) && $result['url'] != $result['redirect_url']) {
        $error .= "Redirecting to another domain - " . $result['redirect_url'];
    }

    if ($ssl && isset($result['ssl_cert_expiry_days'])) {
        $sslCheckResult = ssl_check($result, $result['ssl_cert_expiry_days']);
        if ($sslCheckResult < 2) {
            $error .= " SSL expires in " . $sslCheckResult . " days.";
        }
    }

    if (empty($error)) {
        $message = "Site is operational. HTTP Code: " . $result['http_code'];
        server_update($db, $server_id, true, $message, $result['total_time']);
    } else {
        server_update($db, $server_id, false, $error, $result['total_time']);
    }

    return $result['total_time'];
}

function getCurrentTime() {
    return date('Y-m-d H:i:s');
}

function ssl_check($curl_info, $expiry_days) {
    if (!empty($curl_info) && $expiry_days > 0) {
        $certinfo = openssl_x509_parse($curl_info['Cert']);
        $cert_expiration_date = $certinfo['validTo_time_t'];
        $expiration_time = ($cert_expiration_date - time()) / 86400;
        return $expiration_time <= $expiry_days ? $expiration_time : 1;
    }
    return 0;
}

function ip_ping($host, $timeout) {
    $starttime = microtime(true);
    $output = [];
    $host = parse_url($host, PHP_URL_HOST) ?: $host;
    $cmd = "ping -c 1 -w $timeout $host";
    exec($cmd, $output, $status);
    $endtime = microtime(true);

    if (isset($output[1])) {
        preg_match('/time=(.*?) ms/', $output[1], $matches);
        return isset($matches[1]) ? (float)$matches[1] : -1;
    }
    return $endtime - $starttime;
}

function service_ping($host, $port, $timeout) {
    $starttime = microtime(true);
    $file = fsockopen($host, $port, $errno, $errstr, $timeout);
    $stoptime = microtime(true);
    if (!$file) {
        return -1;  // Socket not opened
    } else {
        fclose($file);
        return ($stoptime - $starttime) * 1000;
    }
}

function server_update($db, $server_id, $success, $message, $latency) {
    $currentTime = getCurrentTime();
    $statusField = $success ? 'last_online' : 'last_offline';
    $query = "UPDATE server_master SET $statusField = '$currentTime', last_output = ?, live = ?, latency = ? WHERE server_id = ?";
    $db->update($query, [$message, (int)$success, $latency, $server_id]);
}

