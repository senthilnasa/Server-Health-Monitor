<?php
use PHPMailer\PHPMailer\PHPMailer;

function send_mail($db,string $subject, string $body)
{
	require_once __DIR__ . '/../../phpmailer/PHPMailer.php';
	require_once __DIR__ . '/../../phpmailer/SMTP.php';

	$mail = new PHPMailer(true);

	try {

		$html_body = "<div>This is a mail from Php sever Monitor<br></div>" . $body . "<br>By <a href='https://senthilnasa.me' target='_blank'>senthil nasa</a>";
		$body = "This is a mail from Php sever Monitor" . $body . "From senthil nasa";
		$datas = $db->select("SELECT `key`,`value` FROM config WHERE `key` IN('mail_password','mail_port','mail_host','mail_id') ORDER BY `key` ");
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

        $toMaillist =[];
        foreach ($db->select("SELECT email FROM user_master ",[]) as $t) {
            $toMaillist[]=$t['email'];
        }
        if($mailHost=="" || $mailPort=="" || $mailId=="" || $mailPassword==""){
            // Send with default mail
            mail(implode(",",$toMaillist),$subject,$body);
            return true;
        }
      

		$mail->isSMTP();
		$mail->Host = $mailHost;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = $mailPort;
		$mail->Username = $mailId;
		$mail->Password = $mailPassword;
		
		$mail->setFrom($mailId, 'Php Server Monitor');
		foreach ($toMaillist as $toMail) {
			$mail->addAddress($toMail);
		}
		$mail->IsHTML(true);
		$mail->Subject =  $subject;
		$mail->Body = $html_body;
		$mail->AltBody = $body;
		$mail->send();
        $iquery="INSERT INTO mail_log(`subject`,`body`,`status`) VALUES(?,?,?)";
        $db->insert($iquery,[$subject,$body,1]);
		
		return true;
	} catch (Exception $e) {
		err( "Error in sending email. Mailer Error: {$mail->ErrorInfo}");
	}
}

function send_telegram_message($db, string $body)
{
    $datas = $db->select("SELECT `key`,`value` FROM config WHERE `key` IN('telegram_token','telegram_chat_id') ORDER BY `key`", []);
    $token="";
    $chatId="";
    foreach ($datas as $data) {
        if($data['key']=='telegram_token'){
            $token=$data['value'];
        }
        if($data['key']=='telegram_chat_id'){
            $chatId=$data['value'];
        }
    }
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($body);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}