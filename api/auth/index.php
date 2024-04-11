<?php

require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/functions.php';


check('fun', 'Type Required');
$fun = $_POST['fun'];

switch ($fun) {
    case 'verify_login':
        check(['login', 'pass'], 'Invalid Request!');
        verify_login($_POST['login'], $_POST['pass']);
        break;

    case 'reset_pass':
        check('login', 'Invalid Request!');
        reset_login($_POST['login']);
        break;

    case 'pass_reset':
        check(['email', 'key', 'password', 'new_password'], 'Invalid Request!');   
        if ($_POST['password'] !== $_POST['new_password']) {
            err('Both passwords should be the same!');
        }
        pass_reset($_POST['email'], $_POST['key'], $_POST['password']);
        break;

    default:
        err('Invalid Request');
        break;
}
