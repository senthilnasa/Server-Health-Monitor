<?php

switch ($_POST['fun']) {
  case 'test_db':
    $host = $_POST['d1'];
    $port = $_POST['d2'];
    $username = $_POST['d3'];
    $password = $_POST['d4'];
    $dbname = $_POST['d5'];
    $res = array();

    $mysqli = new mysqli($host, $username, $password, $dbname, $port);

    if ($mysqli->connect_errno) {
      $res['ok'] = false;
      $res['err'] =
        $mysqli->connect_error;
      comp($res);
    } else {
      $res['data'] = "<?php
      define ('DB_HOST', '" . $host . "' ); //Provide the IP/Host where Mysql server is found
      define ('DB_PORT', '" . $port . "' ); //Provide the Port where Mysql server is found
      define ('DB_USER', '" . $username . "' );//Provide the UserId of Mysql server 
      define ('DB_PASSWORD', '" . $password . "' );//Provide the Password of Mysql server
      define ('DB_NAME', '" . $dbname . "' );//Provide the DB Name of Mysql server
      ?>";
      $res['ok'] = true;
      comp($res);
    }
    $mysqli->close();
    break;


  case 'db_add':
    addDatabase(true);

    break;

  case 'add_user':
    checkDb(true);

    require_once __DIR__ . '/../includes/init.php';
    $db = new CRUD;

    if ($db->select("SELECT count(m.`name`) c FROM user_master m")[0]['c'] > 0) {
      $res['ok'] = false;
      $res['err'] = "User Already Exists !";
      comp($res);
    }
    $salt = salt(12);
    check(['uname', 'name', 'mail', 'pass'], 'Invalid Request');
    extract($_POST);
    
    if ($db->insert("insert into user_master(user_name,name,email,salt,password,role) value(?,?,?,?,SHA1(SHA1(MD5(CONCAT(?,?,?)))),1)", [$uname, $name, $mail, $salt, $salt, $pass, $salt])) {
     complete(true);
    }
    err("Error Occured in creating user");
    break;

  default:
    require '../includes/nono.html';
}


function addDatabase()
{
  $mysqli= checkDb(false);
 
 
  $sql = "USE `" . DB_NAME . "`;
  DROP TABLE IF EXISTS `config`;
  
  CREATE TABLE `config` (
    `key` varchar(255) NOT NULL,
    `value` varchar(255) NOT NULL,
    PRIMARY KEY (`key`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  insert  into `config`(`key`,`value`) values 
  ('email_status','1'),
  ('telegram_status','1'),
  ('telegram_api_token',' ');
  
  
  DROP TABLE IF EXISTS `notification_log`;
  
  CREATE TABLE `notification_log` (
    `server_id` int(11) unsigned NOT NULL,
    `type` enum('email','telegram') NOT NULL,
    `message` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  
  DROP TABLE IF EXISTS `server_master`;
  
  CREATE TABLE `server_master` (
    `server_id` int(11) unsigned NOT NULL ,
    `server_name` varchar(255) NOT NULL,
    `url` varchar(500) NOT NULL,
    `type` enum('ping','service','website') NOT NULL DEFAULT 'service',
    `last_online` timestamp NULL DEFAULT NULL,
    `last_offline` timestamp NULL DEFAULT NULL,
    `last_output` text DEFAULT NULL,
    `last_posstive` text DEFAULT NULL,
    `last_error` text DEFAULT NULL,
    `live` tinyint(1) NOT NULL,
    `latency` float(9,7) DEFAULT NULL,
    `email` tinyint(1) NOT NULL,
    `telegram` tinyint(1) NOT NULL,
    `state` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`server_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  
  INSERT  INTO `server_master`(`server_id`,`server_name`,`url`,`type`,email,`telegram`,`live`,`state`,`created_at`,`updated_at`) VALUES 
  (1,'Senthil Nasa Website','https://senthilnasa.me/','website',0,0,1,1,NOW(),NOW());
  
  DROP TABLE IF EXISTS `server_ping_log`;
  
  CREATE TABLE `server_ping_log` (
    `server_id` int(11) unsigned NOT NULL,
    `state` tinyint(1) unsigned NOT NULL,
    `latency` float(9,7) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  
  /*Table structure for table `user_login_log` */
  
  DROP TABLE IF EXISTS `user_login_log`;
  
  CREATE TABLE `user_login_log` (
    `user_id` int(11) unsigned NOT NULL,
    `ip` text NOT NULL,
    `state` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  
  DROP TABLE IF EXISTS `user_master`;
  
  CREATE TABLE `user_master` (
    `user_id` int(11) unsigned NOT NULL ,
    `name` varchar(255) NOT NULL,
    `user_name` varchar(64) NOT NULL COMMENT 'user''s name',
    `password` varchar(255) DEFAULT NULL COMMENT 'user''s password',
    `telegram_id` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `role` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `unique_username` (`user_name`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  ";

  $res = array();
  if ($mysqli->multi_query($sql)) {
    $res['ok'] = true;
    comp($res);
  } else {
    $res['ok'] = false;
    $res['err'] = "Grant all access to user '" . DB_USER . "' for database '" . DB_NAME . " to continue";
    comp($res);
  }
  $mysqli->close();
}

function checkDb($a)
{
  if (!file_exists(__DIR__ . "/../../config.php")) {
    $res['ok'] = false;
    $res['err'] = "File is not Found config.php in not found home Directory";
    comp($res);
  }
 require __DIR__ . "/../../config.php";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME,DB_PORT);

  if ($mysqli->connect_errno) {
    $res['ok'] = false;
    $res['err'] = "Failed to connect to MySQL: " . $mysqli->connect_error;
    comp($res);
  }
  if ($a) {
    $mysqli->close();
    return true;
  }
  return $mysqli;
}
function comp($res)
{
  header('Content-type: application/json');
  echo json_encode($res);
  die();
}

