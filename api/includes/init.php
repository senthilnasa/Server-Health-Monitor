<?php

require_once __DIR__ . '/response.php';
require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/function.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// if ($_SERVER['REQUEST_METHOD'] != 'POST') {
//   err("Invlaid Request");
// }


if (!isset($_SESSION)) {
    session_name('senthilnasa');
    session_start();
}

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
                if(isset($row['x'])){
                    $row['x']=intval($row['x'])*1000;
                }
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


use PHPMailer\PHPMailer\PHPMailer;

function send_mail(string $subject, string $body, array $to = array())
{
	require_once __DIR__ . '/../../phpmailer/PHPMailer.php';
	require_once __DIR__ . '/../../phpmailer/SMTP.php';

	$mail = new PHPMailer(true);

	try {

		$html_body = "<div>This is a mail from Php sever Monitor<br></div>" . $body . "<br>By <a href='https://senthilnasa.me' target='_blank'>senthil nasa</a>";
		$body = "This is a mail from Php sever Monitor" . $body . "From senthil nasa";
		$db = new CRUD;
		$datas = $db->select("SELECT * FROM config WHERE `key` IN('mail_password','mail_port','mail_port','mail_host','mail_id')",[]);
		$data=array();
		foreach ($datas as $val) {
		$data[]=$val['value'];
		}
		$mail->isSMTP();
		$mail->Host = $data[3];
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = $data[2];
		$mail->Username = $data[0];
		$mail->Password = $data[1];
		
		$mail->setFrom('severs@senthilnasa.me', 'Php Server Monitor');

		foreach ($to as $t) {
			$mail->addAddress($t, 'Admin');
		}
		$mail->IsHTML(true);
		$mail->Subject =  $subject;
		$mail->Body = $html_body;
		$mail->AltBody = $body;
		$mail->send();
		
		return true;
	} catch (Exception $e) {
		err( "Error in sending email. Mailer Error: {$mail->ErrorInfo}");
	}
}


function get_client_ip()
{
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];

	if (filter_var($client, FILTER_VALIDATE_IP)) {
		$ip = $client;
	} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
		$ip = $forward;
	} else {
		$ip = $remote;
	}

	return $ip;
}


function timeAgo($time_ago)
{
// $time_ago = strtotime($time_ago);
$cur_time   = time();
$time_elapsed   = $cur_time - $time_ago;
$seconds    = $time_elapsed ;
$minutes    = round($time_elapsed / 60 );
$hours      = round($time_elapsed / 3600);
$days       = round($time_elapsed / 86400 );
$weeks      = round($time_elapsed / 604800);
$months     = round($time_elapsed / 2600640 );
$years      = round($time_elapsed / 31207680 );
// Seconds
if($seconds <= 60){
	return "just now";
}
//Minutes
else if($minutes <=60){
	if($minutes==1){
		return "one minute ago";
	}
	else{
		return "$minutes minutes ago";
	}
}
//Hours
else if($hours <=24){
	if($hours==1){
		return "an hour ago";
	}else{
		return "$hours hrs ago";
	}
}
//Days
else if($days <= 7){
	if($days==1){
		return "yesterday";
	}else{
		return "$days days ago";
	}
}
//Weeks
else if($weeks <= 4.3){
	if($weeks==1){
		return "a week ago";
	}else{
		return "$weeks weeks ago";
	}
}
//Months
else if($months <=12){
	if($months==1){
		return "a month ago";
	}else{
		return "$months months ago";
	}
}
//Years
else{
	if($years==1){
		return "one year ago";
	}else{
		return "$years years ago";
	}
}
}
