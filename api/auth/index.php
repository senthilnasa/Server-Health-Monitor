<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/init.php';
check('fun','Type Required');
extract($_POST);
switch($fun) {
    case 'verify_login':
    check('login','Login  Required !');
    check('pass','Password Required !');
    extract($_POST);
    verify_login($login,$pass);
    break;

    case 'reset_pass':
    check('login','Login  Required !');
    extract($_POST);
    reset_login($login);
    break;

    case 'pass_reset':
    check('email','Email  Required !');
    check('key','Reset key  Required !');
    check('password','password  Required !');
    check('new_password','new_password  Required !');
    extract($_POST);
    if($password!=$new_password){
        err('Both password should same !');
    }
    pass_reset($email,$key,$password);
    break;
    // echo send_mail('Test mail','Hello world', []);
   
    default:
    err('Invalid Request');
    die();
}