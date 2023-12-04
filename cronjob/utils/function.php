<?php

function website(
    $db,
    $href,
    $header = false,
    $body = true,
    $timeout = null,
    $add_agent = true,
    $website_username = false,
    $website_password = false,
    $request_method = null,
    $post_field = null,
    $server_id,
    $redirect,
    $ssl
) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_NOBODY, (!$body));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_CERTINFO, 1);

    $err = "";

    if (!empty($request_method)) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_method);
    }

    if (!empty($post_field)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
    }

    if (
        $website_username !== false &&
        $website_password !== false &&
        !empty($website_username) &&
        !empty($website_password)
    ) {
        curl_setopt($ch, CURLOPT_USERPWD, $website_username . ":" . $website_password);
    }
    $href = preg_replace('/(.*)(%cachebuster%)/', '$0' . time(), $href);
    curl_setopt($ch, CURLOPT_URL, $href);
    if ($add_agent) {
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36');
    }
    curl_exec($ch);
    $result = curl_getinfo($ch);
    curl_close($ch);
    if ($result['http_code'] == 0) {
        server_update($db, $server_id, 0, 'TIMEOUT ERROR: no response from server');
        return false;
    } else {
        // echo json_encode($result);
        if ($redirect == 0) {

            $location_matches = array();
            preg_match(
                '/([Ll]ocation: )(https*:\/\/)(www.)?([a-zA-Z.:0-9]*)([\/][[:alnum:][:punct:]]*)/',
                $result['url'],
                $href
            );
            if (!empty($location_matches)) {
                $ip_matches = array();
                preg_match(
                    '/(https*:\/\/)(www.)?([a-zA-Z.:0-9]*)([\/][[:alnum:][:punct:]]*)?/',
                    $result['primary_ip'],
                    $ip_matches
                );
                if (strtolower($location_matches[4]) !== strtolower($ip_matches[3])) {
                    $err .= "The IP/URL redirects to another domain.";
                }
            }
            if (strpos($href, $result['url']) === FALSE) {
                $err .= "Reqirecting to other Domain -" . $result['url'];
            }
        }
    }
    if ($ssl) {
        $__a = ssl_check($ssl, $result['ssl_cert_expiry_days']);
        if ($__a < 2) {
            $err .= "SSL Expiries in" . $__a;
        }
    }
    $t = $result['total_time'];
    if ($err == "") {
        $p = "The site is working properly with Content Type - " . $result['content_type'] . "  Http Code -" . $result['http_code'];
        $db->update('UPDATE server_master SET last_online="'. getCurrentTime().'",last_output=?,last_posstive=?,live=1,latency=? where server_id=?', [$p, $p, $t, $server_id]);
    } else {
        $db->update('UPDATE server_master SET last_offline="'. getCurrentTime().'",last_output=?,last_error=?,live=0,latency=? where server_id=?', [$err, $err, $t, $server_id]);
    }

    return $result['total_time'];
}

function getCurrentTime()
{
    return date('Y-m-d H:i:s');
}

function ssl_check($curl_info, $expiry_days)
{
    if (
        !empty($curl_info) &&
        $expiry_days > 0
    ) {
        $certinfo = reset($curl_info);
        $certinfo = openssl_x509_parse($certinfo['Cert']);
        $cert_expiration_date = $certinfo['validTo_time_t'];
        $expiration_time = round((int)($cert_expiration_date - time()) / 86400);
        return time() + (86400 *  $expiry_days);

        if ($expiration_time -  $expiry_days < 0) {
            return $expiration_time -  $expiry_days;
        } elseif ($expiration_time >= 0) {
            return 1;
        } else {
            return $expiration_time;
        }
    }
}


function ip_ping($host, $timeout): float
{
    $host = parse_url($host)['host'];
    $latency = -1;
    $ttl = 100;
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $exec_string = 'ping -n 1 -i ' . $ttl . ' -w ' . ($timeout * 1000) . ' ' . $host;
    } else if (strtoupper(PHP_OS) === 'DARWIN') {
        $exec_string = 'ping -n -c 1 -m ' . $ttl . ' -t ' . $timeout . ' ' . $host;
    } else {
        $exec_string = 'ping -n -c 1 -t ' . $ttl . ' -W ' . $timeout . ' ' . $host . ' 2>&1';
    }
    exec($exec_string, $output, $return);

    $output = array_values(array_filter($output));
    if (!empty($output[1])) {
        $response = preg_match("/time(?:=|<)(?<time>[\.0-9]+)(?:|\s)ms/", $output[1], $matches);
        if ($response > 0 && isset($matches['time'])) {
            $latency = $matches['time'];
        }
    }
    return $latency;
}


function service_ping($host, $port, $timeout): float
{
    $starttime = microtime(true);
    $file      = fsockopen($host, $port, $errno, $errstr, $timeout);
    $stoptime  = microtime(true);
    $status    = 0;
    if (!$file) $status = -1;
    else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
    }
    return $status;
}

function server_update($db, $server_id, $res, $out)
{
    if ($res) {
        $db->update('UPDATE server_master SET last_online="'. getCurrentTime().'", last_posstive=?,live=1,last_output=? WHERE server_id=?', [$out, $out, $server_id]);
    } else {
        $db->update('UPDATE server_master SET last_offline="'. getCurrentTime().'", last_output=?,live=0,last_error=? WHERE server_id=?', [$out, $out, $server_id]);
    }
}

use PHPMailer\PHPMailer\PHPMailer;

function send_mail_warn(string $subject, string $body)
{
	require_once __DIR__ . '/../../phpmailer/PHPMailer.php';
	require_once __DIR__ . '/../../phpmailer/SMTP.php';

	$mail = new PHPMailer(true);

	try {

		$html_body = "<div>This is a mail from Php sever Monitor<br></div>" . $body . "<br>By <a href='https://senthilnasa.me' target='_blank'>senthil nasa</a>";
		$body = "This is a mail from Php sever Monitor" . $body . "From senthil nasa";
		$db = new CRUD;
		$datas = $db->select("SELECT key,value FROM config WHERE `key` IN('mail_password','mail_port','mail_host','mail_id') order by `key`", []);
       $mailHost="";
        $mailPort="";
        $mailId="";
        $mailPassword="";
        foreach ($datas as $data) {
            if($data['key']=='mail_host'){
                $mailHost=$data['value'];
            }
            if($data['key']=='mail_port'){
                $mailPort=$data['value'];
            }
            if($data['key']=='mail_id'){
                $mailId=$data['value'];
            }
            if($data['key']=='mail_password'){
                $mailPassword=$data['value'];
            }
        }

		$mail->isSMTP();
		$mail->Host = $mailHost;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = $mailPort;
		$mail->Username = $mailId;
		$mail->Password = $mailPassword;
		
		$mail->setFrom($mailId, 'Php Server Monitor');
		foreach ($db->select("SELECT email FROM user_master ",[]) as $t) {
			$mail->addAddress($t['email'], 'Admin');
		}
		$mail->IsHTML(true);
		$mail->Subject =  $subject;
		$mail->Body = $html_body;
		$mail->AltBody = $body;
		$mail->send();
        $iquery="INSERT INTO mail_log(`subject`,`body`,`status`) VALUES(?,?,?)";
		
		return true;
	} catch (Exception $e) {
		err( "Error in sending email. Mailer Error: {$mail->ErrorInfo}");
	}
}