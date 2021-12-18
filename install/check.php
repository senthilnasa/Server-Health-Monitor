<?php

if(file_exists(__DIR__."/../config.php")){
    require __DIR__.'/../config.php';

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if($mysqli -> connect_errno) {
        echo "ko";
    }
    else{
        $result = $mysqli->query("SELECT m.`name` FROM user_master m");
        if ($result->num_rows) {
            die('<meta http-equiv="refresh" content="0;url=../auth/" />');
        }else{
            register_shutdown_function('configDone');
           
        }
    }

}
function configDone(){
    echo '<script type="text/javascript">                                 
    $(document).ready( function() { 
        $("#configExtensionDiv").hide();
        $("#configAdminUserDiv").show();
    }); 
    </script> ';
}

$errors = array();
$sucess = array();
function addMessage($msg, $typ)
{
    if ($typ == 'error') {
        array_push($GLOBALS["errors"], $msg);
    } else {
        array_push($GLOBALS["sucess"], $msg);
    }
}

        $phpv = phpversion();
        if (
            version_compare($phpv, '5.5.9', '<') ||
            (version_compare($phpv, '7.0.8', '<') && version_compare($phpv, '7.0.0', '>='))
        ) {
            addMessage('PHP 5.5.9+ or 7.0.8+ is required to run PHP Server Monitor. You\'re using ' . $phpv . '.', 'error');
        } else {
            addMessage('PHP version: ' . $phpv, 'success');
        }
        if (version_compare(PHP_RELEASE_VERSION, '7', '<')) {
            addMessage(
                'PHP 5 reaches the end of life (January 1, 2019), please update to PHP 7.
                PHP supported versions can be found
                <a href="https://secure.php.net/supported-versions.php" target="_blank"
                rel="noopener">here</a>.',
                'warning'
            );
        }
        if (!function_exists('curl_init')) {
            addMessage('PHP is installed without the cURL module. Please install cURL.', 'warning');
        } else {
            addMessage('PHP cURL module found', 'success');
        }
        if (!in_array('mysql', \PDO::getAvailableDrivers())) {

            addMessage('The PDO MySQL driver needs to be installed.', 'error');
        } else {
            addMessage('PHP PDO MySQL driver found', 'success');
        }
        if (!extension_loaded('filter')) {
            addMessage('PHP is installed without the filter module. Please install filter.', 'warning');
        } else {
            addMessage('PHP filter module found', 'success');
        }
        if (!extension_loaded('ctype')) {
            addMessage('PHP is installed without the ctype module. Please install ctype.', 'warning');
        } else {
            addMessage('PHP ctype module found', 'success');
        }
        if (!extension_loaded('hash')) {
            addMessage('PHP is installed without the hash module. Please install hash.', 'warning');
        } else {
            addMessage('PHP hash module found', 'success');
        }
        if (!extension_loaded('json')) {
            addMessage('PHP is installed without the json module. Please install json.', 'warning');
        } else {
            addMessage('PHP json module found', 'success');
        }
        if (!extension_loaded('libxml')) {
            addMessage('PHP is installed without the libxml module. Please install libxml.', 'warning');
        } else {
            addMessage('PHP libxml module found', 'success');
        }
        if (!extension_loaded('openssl')) {
            addMessage('PHP is installed without the openssl module. Please install openssl.', 'warning');
        } else {
            addMessage('PHP openssl module found', 'success');
        }
        if (!extension_loaded('pcre')) {
            addMessage('PHP is installed without the pcre module. Please install pcre.', 'warning');
        } else {
            addMessage('PHP pcre module found', 'success');
        }
        if (!extension_loaded('sockets')) {
            addMessage('PHP is installed without the sockets module. Please install sockets.', 'warning');
        } else {
            addMessage('PHP sockets module found', 'success');
        }
        if (!extension_loaded('xml')) {
            addMessage('PHP is installed without the xml module. Please install xml.', 'warning');
        } else {
            addMessage('PHP xml module found', 'success');
        }
        if (!ini_get('date.timezone')) {
            addMessage(
                'You should set a timezone in your php.ini file (e.g. \'date.timezone = UTC\').
                See <a href="http://www.php.net/manual/en/timezones.php" target="_blank" rel="noopener">this page</a>
                for more info.',
                'warning'
            );
        }

