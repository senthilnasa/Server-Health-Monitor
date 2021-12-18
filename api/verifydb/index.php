<?php

if(!file_exists(__DIR__."/../../config.php")){
   die(0);
}

require __DIR__.'/../../config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if($mysqli -> connect_errno) {
    die(1);
}
else{
    die(0);
}