<!DOCTYPE html>
<html>

<head>
    <title>Server Health Monitor | Admin </title>
    <link type="text/css" rel="stylesheet" href="<?php echo $GLOBALS['_path'] ?>assets/fonts/material-icon/material-icon.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $GLOBALS['_path'] ?>assets/css/materialize.min.css" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="<?php echo $GLOBALS['_path'] ?>assets/css/style.css" />
     <link type="text/css" rel="stylesheet" href="<?php echo $GLOBALS['_path'] ?>assets/css/jtable.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo $GLOBALS['_path'] ?>assets/css/plane.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex">
</head>

<body style="display: none;">
    <header>
        <div class="navbar-fixed">
            <nav class="white ">
                <div class="nav-wrapper">
                    <a class="brand-logo center ">
                        <h5 class="black-text flow-text">Bitsathy Server Monitor</h5>
                    </a>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="../../auth/logout/" class="blue-grey-text text-darken-4 "><i class="material-icons">login</i></a></li>
                    </ul>
                    <a href="#" data-target="slide-out" class="sidenav-trigger blue-grey-text text-darken-4 "><i class="material-icons ">menu</i></a>
                </div>
                <div class="nav-content">
                    <div class="progress m-0 blue" id="progress">
                        <div class="indeterminate white"></div>
                    </div>
                </div>

            </nav>
        </div>
        <ul id="slide-out" class="sidenav sidenav-fixed">
            <li>
                <div class="center" style="height: 200px;">
                    <img src="/assets/images/head.png" width="70%">
                </div>
            </li>
            <div class="divider"></div>
            <li><a class="subheader">Menu</a></li>
            <li><a href="../dashboard/"><i class="material-icons">dashboard</i>Dashboard</a></li>
            <li><a href="../servers/"><i class="material-icons">dns</i>Server Management</a></li>
            <li><a href="../users/"><i class="material-icons">assignment_ind</i>User Management</a></li>
            <li><a href="../notification/"><i class="material-icons">notification_important</i>Notification Log</a></li>
            <li><a href="../log/"><i class="material-icons">login</i>Login Log</a></li>
            <div class="divider" style="margin: unset;"></div>
            <li>
                <a class="waves-effect red-text" href="../../auth/logout/"><i class="material-icons red-text">power_settings_news</i>Logout</a>
            </li>
        </ul>
    </header>