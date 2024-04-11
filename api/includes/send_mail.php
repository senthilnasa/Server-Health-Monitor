<?php

use PHPMailer\PHPMailer\PHPMailer;
/**
 * Sends an email using PHPMailer.
 *
 * @param string $subject Email subject.
 * @param string $body Email body.
 * @param array $to Recipient addresses.
 * @return bool Status of the email sending.
 */
 function send_mail(string $subject, string $body, array $to = []): bool
 {
	 require_once __DIR__ . '/../../phpmailer/PHPMailer.php';
	 require_once __DIR__ . '/../../phpmailer/SMTP.php';
 
	 $mail = new PHPMailer(true);
 
	 try {
		 $mail->isSMTP();
		 $config = (new CRUD())->select("SELECT * FROM config WHERE `key` IN ('mail_host', 'mail_port', 'mail_username', 'mail_password')");
 
		 $mailConfig = array_column($config, 'value', 'key');
		 $mail->Host = $mailConfig['mail_host'];
		 $mail->SMTPAuth = true;
		 $mail->Port = $mailConfig['mail_port'];
		 $mail->Username = $mailConfig['mail_username'];
		 $mail->Password = $mailConfig['mail_password'];
		 $mail->setFrom('severs@senthilnasa.me', 'Php Server Monitor');
 
		 foreach ($to as $recipient) {
			 $mail->addAddress($recipient);
		 }
 
		 $mail->isHTML(true);
		 $mail->Subject = $subject;
		 $mail->Body = "<div>This is a mail from PHP Server Monitor<br></div>" . $body;
		 $mail->AltBody = strip_tags($body);
 
		 return $mail->send();
	 } catch (\Exception $e) {
		 err("Error in sending email: " . $mail->ErrorInfo);
		 return false;
	 }
 }