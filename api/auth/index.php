<?php
require_once __DIR__ . '/../includes/init.php';
check('fun','Type Required');
extract($_POST);
switch($fun) {
    case 'verify_login':
    check(['login','pass'],'Invalid Request !');   
    extract($_POST);
    verify_login($login,$pass);
    break;

    case 'reset_pass':
    check('login','Invalid Request !');
    extract($_POST);
    reset_login($login);
    break;

    case 'pass_reset':
    check(['email','key','password','new_password',],'Invalid Request !');   
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