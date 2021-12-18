<?php
//Auth Function


function verify_login($login, $pass)
{
	$db = new CRUD;
	$data = $db->select('SELECT name,user_id FROM user_master WHERE user_name=? and password=SHA1(SHA1(MD5(CONCAT(salt,?,salt))))', [$login, $pass]);
	if (sizeof($data)) {
		if ($data[0]['user_id']) {
			$_SESSION['user_id']=$data[0]['user_id'];
			$_SESSION['name']=$data[0]['name'];
			$_SESSION['islogin']=true;
			log_user($db,$login,'1');
			complete(true);
		} else {
			log_user($db, $login, '0');
			err('Invalid Credentials');
		}
	} else {
		log_user($db, $login, '0');
		err('Invalid Credentials');
	}
}



function reset_login($login)
{
	$db = new CRUD;
	$data = $db->select('SELECT email,user_id,name FROM user_master WHERE user_name=?', [$login]);
	if (sizeof($data)) {
		if ($data[0]['user_id']) {
			$sal = salt();
			if ($db->inserts('INSERT INTO password_reset(email,key_auth) VALUES((SELECT email FROM user_master WHERE user_name=?),?)', [$login, $sal])) {
				$a=$data[0]['email'];
				$c = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].'/auth/reset/?key=' . $sal . '&email=' . $a .'&action=reset';
				$b="Hello ".$data[0]['name'].",<br> You recently requested to reset your password for your PHP Server monitor. <br>Use the  below link to reset it. This password reset is only valid for the next 24 hours.<br> url:-".$c;
				send_mail('Reset Password Link !',$b, [$a]);
				complete('Reset link was sent to your mail id !');
			}
		}
	}

	err('Invalid User Name');
}

function pass_reset($email,$key,$password){
	$db = new CRUD;
	if ($db->update('UPDATE  password_reset set state=0 WHERE email=? AND state=1 AND exp_date>=NOW() - INTERVAL 1 DAY AND key_auth=? LIMIT 1', [$email,$key])) {
		$salt=salt(12);
		if($db->update('UPDATE user_master SET `password`=SHA1(SHA1(MD5(CONCAT(?,?,?)))) , salt=? WHERE email=?', [$salt,$password,$salt,$salt,$email])){
			complete(true);
		}
	}
	err("Token Expired !");
}

function log_user($db, $id, $state)
{
	$ip = get_client_ip();
	$db->insert("INSERT INTO user_login_log(user_id,state,url) VALUES((SELECT `user_id` FROM user_master WHERE user_name=?),?,?)", [$id, $state, $ip]);
}

function salt($length = 10)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}


//Data Function
function dashboard_data()
{
	$db = new CRUD;
	$data = $db->select("
	SELECT(SELECT COUNT(*) FROM server_master WHERE state=1)AS d1,
	(SELECT  COUNT(*) FROM server_master WHERE live=1 AND state=1)AS d2,
	(SELECT  COUNT(*) FROM server_master WHERE live=0 AND state=1)AS d3,
	(SELECT created_at FROM server_ping_log ORDER BY created_at DESC LIMIT 1)AS d4
	");
	$data[0]['d4'] = timeAgo(strtotime($data[0]['d4']));
	$data[0]['d3'] = floatval($data[0]['d3']);
	$data[0]['d3'] = intval($data[0]['d3']);
	return $data;
}


function dashboard_chart_online()
{
	$db = new CRUD;
	return $db->select("SELECT ROUND(UNIX_TIMESTAMP(created_at))AS x,COUNT(DISTINCT  server_id)AS y,FLOOR(UNIX_TIMESTAMP(created_at)/(15 * 60)) AS timekey FROM server_ping_log WHERE state=1 GROUP BY timekey");
}

function dashboard_chart_offline()
{
	$db = new CRUD;
	return $db->select("SELECT  ROUND(UNIX_TIMESTAMP(created_at))AS x,COUNT(DISTINCT  server_id)AS y,FLOOR(UNIX_TIMESTAMP(created_at)/(15 * 60)) AS timekey FROM server_ping_log WHERE state=0 GROUP BY timekey");
}

function servers_dash_list($a)
{
	$db = new CRUD;
	return $db->select("SELECT server_name,server_id ,ROUND(UNIX_TIMESTAMP(updated_at) * 1000)AS tim  FROM server_master WHERE live=? AND state=1", [$a]);
}

function server_list()
{
	$db = new CRUD;
	return $db->select("SELECT `url` ip,`server_id`,`server_name`,`type`,`last_offline`,`last_online`,`state`,`latency` FROM server_master");
}

function users_list()
{
	$db = new CRUD;
	return $db->select("SELECT user_id,user_name,name,telegram_id,email,role,created_at FROM user_master");
}

function user_update($user_id, $mail, $name, $tid)
{
	$db = new CRUD;
	$db->update("UPDATE user_master SET name=?, telegram_id=?,email=? WHERE user_id=?", [$name, $tid, $mail, $user_id]);
	return true;
}

function user_delete($user_id)
{
	$db = new CRUD;
	if ($db->update("DELETE FROM  user_master WHERE user_id=? and role=1", [$user_id])) {
		return true;
	}

	return 'Falied to delete user';
}
function user_add($uname, $mail, $name, $tid,$pass)
{
	$db = new CRUD;
	$salt=salt(12);
	if ($db->insert("insert into user_master(user_name,`name`,`email`,`telegram_id`,`salt`,password) value(?,?,?,?,?,SHA1(SHA1(MD5(CONCAT(?,?,?)))))", [$uname, $name, $mail, $tid,$salt,$salt,$pass,$salt])) {
		return true;
	}
	return 'Falied to add urser';
}

function login_log()
{
	$db = new CRUD;
	$u = $_SESSION['user_id'];
	return $db->select("SELECT ROUND(UNIX_TIMESTAMP(created_at))AS x,`state`, ip FROM `user_login_log` WHERE user_id=? order by created_at desc ", [$u]);
}

function notification_log()
{
	$db = new CRUD;
	return $db->select("SELECT m.*,ROUND(UNIX_TIMESTAMP(m.created_at) * 1000)AS x,s.`server_name` FROM notification_log m INNER JOIN server_master s ON s.server_id=m.server_id");
}


function server_details($sid)
{
	$db = new CRUD;
	return $db->select("SELECT * FROM server_master WHERE server_id=?", [$sid]);
}


function server_offline($sid)
{
	$db = new CRUD;
	return $db->select("SELECT  ROUND(UNIX_TIMESTAMP(created_at))AS x ,latency AS y,FLOOR(UNIX_TIMESTAMP(created_at)/(15 * 60)) AS timekey FROM server_ping_log WHERE state=0 and server_id=? GROUP BY timekey", [$sid]);
}

function server_online($sid)
{
	$db = new CRUD;
	return $db->select("SELECT  ROUND(UNIX_TIMESTAMP(created_at))AS x ,latency AS y,FLOOR(UNIX_TIMESTAMP(created_at)/(15 * 60)) AS timekey FROM server_ping_log WHERE state=1 and server_id=? GROUP BY timekey", [$sid]);
}



function server_latency($sid)
{
	$db = new CRUD;
	$rows = $db->select("SELECT MIN(latency)AS x,MAX(latency)AS y, AVG(latency)AS z,DATE(`created_at`)AS d FROM `server_ping_log` WHERE server_id=? GROUP BY DATE(created_at)", [$sid]);
	$ress1 = array();
	$ress2 = array();
	$ress3 = array();
	$ress = array();
	$a = 0;
	foreach ($rows as $row) {
		$ress1[$a]['x'] = $row['d'];
		$ress1[$a]['y'] = $row['x'];
		$ress2[$a]['x'] = $row['d'];
		$ress2[$a]['y'] = $row['y'];
		$ress3[$a]['x'] = $row['d'];
		$ress3[$a]['y'] = $row['z'];
		$a++;
	}
	$ress['x'] = $ress1;
	$ress['y'] = $ress2;
	$ress['z'] = $ress3;
	return $ress;
}

function server_report($sid)
{
	$db = new CRUD;
	return $db->select("SELECT(
	SELECT COUNT(*)/(SELECT COUNT(*) FROM `server_ping_log` WHERE `server_id`=?)*100 FROM `server_ping_log` WHERE `server_id`=? AND state=1) AS z,
	(SELECT COUNT(*)/(SELECT COUNT(*) FROM `server_ping_log` WHERE `server_id`=?)*100 FROM `server_ping_log` WHERE `server_id`=? AND state=0)AS y
	", [$sid, $sid, $sid, $sid]);
}


function server_add($server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out)
{
	$db = new CRUD;
	if ($db->insert("INSERT INTO server_master(server_name,url,type,telegram,state,email,threshold,time_out) VALUES(?,?,?,?,?,?,?,?)", [$server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out])) {
		return true;
	}
	return false;
}


function server_add_s($server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out,$port)
{
	$db = new CRUD;
	if ($db->insert("INSERT INTO server_master(server_name,url,type,telegram,state,email,threshold,time_out,port) VALUES(?,?,?,?,?,?,?,?,?)", [$server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out,$port])) {
		return true;
	}
	return false;
}

function server_add_w($server_name,$ip,$type,$telegram,$state,$email,$threshold,$time_out,$method,$post_field,$header_name,$header_value,$redirect_type,$ssl,$user_name,$user_pass)
{
	$db = new CRUD;
	if ($db->insert("INSERT INTO server_master(server_name,url,type,telegram,state,email,threshold,time_out,method,post_field,header_name,header_value,redirect_type,`ssl`,`user_name`,`user_pass`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [$server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out,$method,$post_field,$header_name,$header_value,$redirect_type,$ssl,$user_name,$user_pass])) {
		return true;
	}
	return false;
}
function server_update($sid, $server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out)
{
	$db = new CRUD;
	$db->update("UPDATE server_master SET `server_name`=?,url=?,`port`=NULL ,`type`=?,telegram=?,state=?,email=?,method=null,post_field=NULL,header_name=NULL,header_value=NULL,user_name=NULL,user_pass=NULL,redirect_type=0,threshold=?,`ssl`=0,time_out=? WHERE server_id=?", [$server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out, $sid]);
	return true;
}

function server_update_s($sid, $server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out,$port)
{
	$db = new CRUD;
	$db->update("UPDATE server_master SET `server_name`=?,url=? ,`type`=?,telegram=?,state=?,email=?,method=null,post_field=NULL,header_name=NULL,header_value=NULL,user_name=NULL,user_pass=NULL,redirect_type=0,threshold=?,`ssl`=0,time_out=?,`port`=? WHERE server_id=?", [$server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out,$port ,$sid]);
	return true;
}

function server_update_w($sid,$server_name,$ip,$type,$telegram,$state,$email,$threshold,$time_out,$method,$post_field,$header_name,$header_value,$user_name,$user_pass,$redirect_type,$ssl)
{
	$db = new CRUD;
	$db->update("UPDATE server_master SET `server_name`=?,url=?,`port`=NULL ,`type`=?,telegram=?,state=?,email=?,threshold=?,time_out=?,method=?,post_field=?,header_name=?,header_value=?,user_name=?,user_pass=?,redirect_type=?,`ssl`=? WHERE server_id=?",[$server_name, $ip, $type, $telegram, $state, $email,$threshold,$time_out,$method,$post_field,$header_name,$header_value,$user_name,$user_pass,$redirect_type,$ssl, $sid]);
	return true;
}
function server_delete($sid)
{
	$db = new CRUD;
	$db->update("DELETE FROM server_ping_log WHERE server_id=?", [$sid]);
	$db->update("DELETE FROM server_master WHERE server_id=?", [$sid]);
	return true;
}