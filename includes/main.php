<?php
date_default_timezone_set("Asia/Kolkata");

header('X-Frame-Options: SAMEORIGIN');
header('X-Powered-By: senthilnasa.site');
$_home = '/';
$http = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$_path = $http . $_SERVER['HTTP_HOST'] . $_home;

$selectedMenu = 0;

if (!isset($_SESSION)) {
    session_name('senthilnasa');
    session_start();
}

extract($_REQUEST);


function heads()
{
    require 'header.php';
}

function footer()
{
    require 'footer.php';
}
// if (in_array('admin', explode('/', $_SERVER['REQUEST_URI']))) {
//     if (!isset($_SESSION['mail'])) {
//         header('Location:/auth/', true);
//         die();
//     }
// }

