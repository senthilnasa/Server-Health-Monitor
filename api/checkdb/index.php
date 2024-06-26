<?php
// Switch to handle different POST requests based on 'fun' parameter
switch ($_POST['fun']) {
  // Test database connection
  case 'test_db':
    // Retrieve connection parameters from POST request
    $host = $_POST['d1'];
    $port = $_POST['d2'];
    $username = $_POST['d3'];
    $password = $_POST['d4'];
    $dbname = $_POST['d5'];

    // Initialize response array
    $res = array();

    // Create mysqli object for database connection
    $mysqli = new mysqli($host, $username, $password, $dbname, $port);

    // Check connection
    if ($mysqli->connect_errno) {
      $res['ok'] = false;
      $res['err'] = $mysqli->connect_error;
      comp($res);
    } else {
      // On successful connection, return database connection details
      $res['data'] = "<?php\n" .
      "define('DB_HOST', '$host');\n" .
      "define('DB_PORT', '$port');\n" .
      "define('DB_USER', '$username');\n" .
      "define('DB_PASSWORD', '$password');\n" .
      "define('DB_NAME', '$dbname');\n" .
      "?>";
      $res['ok'] = true;
      comp($res);
    }

    // Close database connection
    $mysqli->close();
    break;

  // Handle database addition
  case 'db_add':
    addDatabase(true);
    break;

  // Handle user addition
  case 'add_user':
    checkDb(true);
    require_once __DIR__ . '/../includes/init.php';
    $db = new CRUD();

    // Check if user already exists
    if ($db->select("SELECT count(m.`name`) c FROM user_master m")[0]['c'] > 0) {
      $res['ok'] = false;
      $res['err'] = "User Already Exists!";
      comp($res);
    }

    // Generate a salt for password hashing
    $salt = salt(12);
    check(['uname', 'name', 'mail', 'pass'], 'Invalid Request');
    extract($_POST);

    // Insert new user into the database
    if ($db->insert("INSERT INTO user_master(user_name, name, email, salt, password, role) VALUES (?, ?, ?, ?, SHA1(SHA1(MD5(CONCAT(?, ?, ?)))), 1)", [$uname, $name, $mail, $salt, $salt, $pass, $salt])) {
      complete(true);
    }
    err("Error Occurred in creating user");
    break;

  // Default case for invalid 'fun' parameter
  default:
    require '../includes/nono.html';
}

// Function to add database
function addDatabase() {
  $mysqli = checkDb(false);
  // Database creation and table initialization SQL
  // This should ideally be placed in a separate SQL file for better maintenance
  $sql = "SET FOREIGN_KEY_CHECKS=0 ;
  DROP TABLE IF EXISTS `config`;

  CREATE TABLE `config` (
    `key` varchar(255) NOT NULL,
    `value` varchar(255) NOT NULL,
    PRIMARY KEY (`key`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  /*Data for the table `config` */
  
  insert  into `config`(`key`,`value`) values 
  ('email_status','0'),
  ('mail_host',''),
  ('mail_id',''),
  ('mail_password',''),
  ('mail_port',''),
  ('telegram_api_token',' '),
  ('telegram_status','1');
  
  /*Table structure for table `notification_log` */
  
  DROP TABLE IF EXISTS `notification_log`;
  
  CREATE TABLE `notification_log` (
    `server_id` int(11) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `type` enum('email','telegram') NOT NULL,
    `message` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `user_id` (`user_id`),
    KEY `server_id` (`server_id`),
    CONSTRAINT `notification_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`user_id`),
    CONSTRAINT `notification_log_ibfk_2` FOREIGN KEY (`server_id`) REFERENCES `server_master` (`server_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  /*Data for the table `notification_log` */
  
  /*Table structure for table `server_master` */
  
  DROP TABLE IF EXISTS `server_master`;
  
  CREATE TABLE `server_master` (
    `server_id` int(11) unsigned NOT NULL,
    `server_name` varchar(255) NOT NULL,
    `url` varchar(500) NOT NULL,
    `type` enum('ping','service','website') NOT NULL DEFAULT 'service',
    `last_online` timestamp NULL DEFAULT NULL,
    `last_offline` timestamp NULL DEFAULT NULL,
    `last_output` text,
    `last_posstive` text,
    `last_error` text,
    `live` tinyint(1) NOT NULL,
    `latency` float(9,7) DEFAULT NULL,
    `method` varchar(255) DEFAULT NULL,
    `post_field` varchar(255) DEFAULT NULL,
    `header_name` varchar(255) DEFAULT NULL,
    `header_value` varchar(255) DEFAULT NULL,
    `user_name` varchar(255) DEFAULT NULL,
    `user_pass` varchar(255) DEFAULT NULL,
    `redirect_type` tinyint(4) DEFAULT NULL,
    `port` varchar(255) DEFAULT NULL,
    `threshold` int(11) DEFAULT NULL,
    `ssl` tinyint(4) DEFAULT NULL,
    `time_out` int(11) DEFAULT NULL,
    `telegram` tinyint(1) NOT NULL,
    `email` tinyint(1) NOT NULL,
    `state` tinyint(1) NOT NULL DEFAULT '1',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`server_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  /*Data for the table `server_master` */
  
  insert  into `server_master`(`server_id`,`server_name`,`url`,`type`,`last_online`,`last_offline`,`last_output`,`last_posstive`,`last_error`,`live`,`latency`,`method`,`post_field`,`header_name`,`header_value`,`user_name`,`user_pass`,`redirect_type`,`port`,`threshold`,`ssl`,`time_out`,`telegram`,`email`,`state`,`created_at`,`updated_at`) values 
  (1,'Senthil Nasa Website','https://senthilnasa.me/','ping','2023-12-04 12:47:42',NULL,'The site is working properly with Content Type - text/html  Http Code -200','The site is working properly with Content Type - text/html  Http Code -200',NULL,1,1.0319999,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,1,0,30,0,0,1,'2023-12-04 12:27:13','2023-12-04 14:11:43');
  
  /*Table structure for table `server_ping_log` */
  
  DROP TABLE IF EXISTS `server_ping_log`;
  
  CREATE TABLE `server_ping_log` (
    `server_id` int(11) unsigned NOT NULL,
    `state` tinyint(1) unsigned NOT NULL,
    `latency` float(9,7) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `server_id` (`server_id`),
    CONSTRAINT `server_ping_log_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `server_master` (`server_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  /*Data for the table `server_ping_log` */
  
  insert  into `server_ping_log`(`server_id`,`state`,`latency`,`created_at`) values 
  (1,1,1.0319999,'2023-12-04 12:47:42');
  
  /*Table structure for table `user_login_log` */
  
  DROP TABLE IF EXISTS `user_login_log`;
  
  CREATE TABLE `user_login_log` (
    `user_id` int(11) unsigned NOT NULL,
    `ip` text NOT NULL,
    `state` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `user_id` (`user_id`),
    CONSTRAINT `user_login_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_master` (`user_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  

  
  /*Table structure for table `user_master` */
  
  DROP TABLE IF EXISTS `user_master`;
  
  CREATE TABLE `user_master` (
    `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `user_name` varchar(120) NOT NULL COMMENT 'user''s name',
    `salt` varchar(30) NOT NULL,
    `password` varchar(255) DEFAULT NULL COMMENT 'user''s password',
    `telegram_id` varchar(255) DEFAULT NULL,
    `email` varchar(255) NOT NULL,
    `role` tinyint(1) NOT NULL DEFAULT '1',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `unique_username` (`user_name`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  

    set FOREIGN_KEY_CHECKS=1;";

  $res = array();
  if ($mysqli->multi_query($sql)) {
    $res['ok'] = true;
    comp($res);
  } else {
    $res['ok'] = false;
    $res['err'] = "Grant all access to user '" . DB_USER . "' for database '" . DB_NAME . "' to continue";
    comp($res);
  }
  $mysqli->close();
}

// Function to check database connection
function checkDb($a) {
  if (!file_exists(__DIR__ . "/../../config.php")) {
    $res['ok'] = false;
    $res['err'] = "File not Found: config.php is not found in the home directory";
    comp($res);
  }
  require __DIR__ . "/../../config.php";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

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

// Function to complete the response and exit
function comp($res) {
  header('Content-type: application/json');
  echo json_encode($res);
  exit;
}
?>
