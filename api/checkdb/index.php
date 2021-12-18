<?php
error_reporting(0);
@ini_set('display_errors', 0);

if($_POST['fun']=='verify_login'){

    $host = $_POST['d1'];
    $port = $_POST['d2'];
    $username = $_POST['d3'];
    $password = $_POST['d4'];
    $db = $_POST['d5'];
    $res=array();

    $mysqli = new mysqli($host, $username, $password,$db,$port);
    
    if($mysqli -> connect_errno) {
      $res['ok'] = false;
      $res['err'] = 
      $mysqli->connect_error;
      co($res);
    }else{
      $res['data'] = "<?php
      define ('DB_HOST', '".$host."' ); //Provide the IP/Host where Mysql server is found
      define ('DB_PORT', '".$host."' ); //Provide the IP/Host where Mysql server is found
      define ('DB_USER', '".$username."' );//Provide the UserId of Mysql server 
      define ('DB_PASSWORD', '".$password."' );//Provide the Password of Mysql server
      define ('DB_NAME', '".$db."' );//Provide the DB Name of Mysql server
      ?>";
      $res['ok'] = true;
      co($res);
    } 
    $mysqli ->close();

}

if($_POST['fun']=='db_add'){

  $res=array();

  if(!file_exists("../../config.php")){
    $res['ok'] = false;
    $res['err'] = "File is not Found config.php in not found home Directory";
    co($res);
  }

  require '../../config.php';
  
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if($mysqli -> connect_errno) {
    $res['ok'] = false;
    $res['err'] = "Failed to connect to MySQL: " . $mysqli -> connect_error;
    co($res);
  }
  else{
   $sql = "USE `".DB_NAME."`;
    DROP TABLE IF EXISTS `config`;
    
    CREATE TABLE `config` (
      `key` varchar(255) NOT NULL,
      `value` varchar(255) NOT NULL,
      PRIMARY KEY (`key`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
    
    insert  into `config`(`key`,`value`) values 
    ('email_status','1'),
    ('telegram_status','1'),
    ('telegram_api_token','909395794:AAF2f_6DgALwThMSBh3q3yqN5xh-XtXwirM'),
    ('google_public','909395794:AAF2f_6DgALwThMSBh3q3yqN5xh-XtXwirM'),
    ('google_private','909395794:AAF2f_6DgALwThMSBh3q3yqN5xh-XtXwirM'),
    ('script_url','1');
    
    
    DROP TABLE IF EXISTS `notification_log`;
    
    CREATE TABLE `notification_log` (
      `server_id` int(11) unsigned NOT NULL,
      `type` enum('email','telegram') NOT NULL,
      `message` text NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp()
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

    
    DROP TABLE IF EXISTS `server_master`;
    
    CREATE TABLE `server_master` (
      `server_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
    ) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
    
    
    insert  into `server_master`(`server_id`,`server_name`,`url`,`type`,`last_online`,`last_offline`,`last_output`,`last_posstive`,`last_error`,`live`,`latency`,`email`,`telegram`,`state`,`created_at`,`updated_at`) values 
    (1,'Bitsathy Main Site','https://bitsathy.ac.in/','service','2020-08-20 10:06:31','2020-08-20 10:06:33','','','',1,1.0000000,0,0,1,'2020-08-20 10:05:16','2020-10-01 10:22:21');
    
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
      `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `user_name` varchar(64) NOT NULL COMMENT 'user''s name',
      `password` varchar(255) DEFAULT NULL COMMENT 'user''s password',
      `telegram_id` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL,
      `role` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`user_id`),
      UNIQUE KEY `unique_username` (`user_name`)
    ) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
    
    /*Data for the table `user_master` */
    
    insert  into `user_master`(`user_id`,`name`,`user_name`,`password`,`telegram_id`,`email`,`role`,`created_at`) values 
    (1,'Admin','root','-','senthilnasa','senthilprabu005@gmail.com',0,'2020-08-20 10:20:50');
    
    ";
    
    // echo $sql;
    if ($mysqli -> multi_query($sql)) {
      $res['ok'] = true;
      co($res);
    } else {
      $res['ok'] = false;
      $res['err'] = "Grant all access to user '".DB_USER."' to proceed !";
      co($res);
    }
    $mysqli ->close();
  }
  
}

else{
  require'../includes/nono.html';
}

function co($res){
  header('Content-type: application/json');
  echo json_encode($res);
  die();
}