<?php
if(file_exists(__DIR__."/../config.php")){
    require __DIR__.'/../config.php';

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if($mysqli -> connect_errno) {
        header("Location: ../install", true);
        die();
    }
    else{
        $result = $mysqli->query("SELECT m.`name` FROM user_master m");
        if ($result->num_rows==0) {
            header("Location: ../install", true);
            die();
        }
    }

}else{
    header("Location: ../install", true);
    die();
}

if (!isset($_SESSION)) {
    session_name('senthilnasa');
    session_start();
}
if (isset($_SESSION['islogin']) &&  $_SESSION['islogin'] == true) {
    header("Location: ../admin/dashboard/", true);
    die();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Server Health Monitor | Login </title>
    <link type="text/css" rel="stylesheet" href="../assets/fonts/material-icon/material-icon.css">
    <link type="text/css" rel="stylesheet" href="../assets/css/materialize.min.css" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="../assets/css/style.css" />
    <link type="text/css" rel="stylesheet" href="../assets/css/assets/plane.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex">
</head>

<body class="grade">

    <div class="clouds">

        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="762px" height="331px" viewBox="0 0 762 331" enable-background="new 0 0 762 331" xml:space="preserve" class="cloud big front slowest">
            <path fill="#FFFFFF" d="M715.394,228h-16.595c0.79-5.219,1.201-10.562,1.201-16c0-58.542-47.458-106-106-106
    c-8.198,0-16.178,0.932-23.841,2.693C548.279,45.434,488.199,0,417.5,0c-84.827,0-154.374,65.401-160.98,148.529
    C245.15,143.684,232.639,141,219.5,141c-49.667,0-90.381,38.315-94.204,87H46.607C20.866,228,0,251.058,0,279.5
    S20.866,331,46.607,331h668.787C741.133,331,762,307.942,762,279.5S741.133,228,715.394,228z" />
        </svg>
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="762px" height="331px" viewBox="0 0 762 331" enable-background="new 0 0 762 331" xml:space="preserve" class="cloud distant smaller">
            <path fill="#FFFFFF" d="M715.394,228h-16.595c0.79-5.219,1.201-10.562,1.201-16c0-58.542-47.458-106-106-106
    c-8.198,0-16.178,0.932-23.841,2.693C548.279,45.434,488.199,0,417.5,0c-84.827,0-154.374,65.401-160.98,148.529
    C245.15,143.684,232.639,141,219.5,141c-49.667,0-90.381,38.315-94.204,87H46.607C20.866,228,0,251.058,0,279.5
    S20.866,331,46.607,331h668.787C741.133,331,762,307.942,762,279.5S741.133,228,715.394,228z" />
        </svg>

        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="762px" height="331px" viewBox="0 0 762 331" enable-background="new 0 0 762 331" xml:space="preserve" class="cloud small slow">
            <path fill="#FFFFFF" d="M715.394,228h-16.595c0.79-5.219,1.201-10.562,1.201-16c0-58.542-47.458-106-106-106
    c-8.198,0-16.178,0.932-23.841,2.693C548.279,45.434,488.199,0,417.5,0c-84.827,0-154.374,65.401-160.98,148.529
    C245.15,143.684,232.639,141,219.5,141c-49.667,0-90.381,38.315-94.204,87H46.607C20.866,228,0,251.058,0,279.5
    S20.866,331,46.607,331h668.787C741.133,331,762,307.942,762,279.5S741.133,228,715.394,228z" />
        </svg>

        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="762px" height="331px" viewBox="0 0 762 331" enable-background="new 0 0 762 331" xml:space="preserve" class="cloud distant super-slow massive">
            <path fill="#FFFFFF" d="M715.394,228h-16.595c0.79-5.219,1.201-10.562,1.201-16c0-58.542-47.458-106-106-106
    c-8.198,0-16.178,0.932-23.841,2.693C548.279,45.434,488.199,0,417.5,0c-84.827,0-154.374,65.401-160.98,148.529
    C245.15,143.684,232.639,141,219.5,141c-49.667,0-90.381,38.315-94.204,87H46.607C20.866,228,0,251.058,0,279.5
    S20.866,331,46.607,331h668.787C741.133,331,762,307.942,762,279.5S741.133,228,715.394,228z" />
        </svg>

        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="762px" height="331px" viewBox="0 0 762 331" enable-background="new 0 0 762 331" xml:space="preserve" class="cloud slower">
            <path fill="#FFFFFF" d="M715.394,228h-16.595c0.79-5.219,1.201-10.562,1.201-16c0-58.542-47.458-106-106-106
    c-8.198,0-16.178,0.932-23.841,2.693C548.279,45.434,488.199,0,417.5,0c-84.827,0-154.374,65.401-160.98,148.529
    C245.15,143.684,232.639,141,219.5,141c-49.667,0-90.381,38.315-94.204,87H46.607C20.866,228,0,251.058,0,279.5
    S20.866,331,46.607,331h668.787C741.133,331,762,307.942,762,279.5S741.133,228,715.394,228z" />
        </svg>

    </div>

    <div class="container rCenter center " style="margin-top: 1%;">
        <div class="row ">
            <div class="col s12 m6 offset-m3 l6 offset-l3 card ">
                <div class="pt-4 pb-4">
                    <div class="row">
                        <a href="#!"><img class="col s12" src="../assets/images/logo.png"></a>
                    </div>
                    <!-- Auth Tab start -->
                    <div class="p-1 pt-3">

                        <center>
                            <h5>LOGIN</h5>
                        </center>
                        <div class="row center">

                            <div class="input-field col s12">
                                <i class="material-icons prefix">assignment_ind</i>
                                <input id="login_id" autocomplete="off" name="loginEmail" type="text" class="validate" required>
                                <label for="login_id">User Name</label>
                            </div>

                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock</i>
                                <input id="login-pass" name="loginPass" type="password" class="validate" required>
                                <label for="login-pass">Password</label>
                            </div>
                            <div class="col s11 m6" style="float: right;">
                                <a class="blue-text" href="forget/"><b>Forgot Password?</b></a>
                                <br>
                                <br>
                            </div>
                            <div class="input-field col s12 center">
                                <button class="btn waves-effect waves-light" id="loginSubmit" onclick="verifyPass()">Login
                                    <i class="material-icons right">exit_to_app</i>
                                </button>
                                <div class="preloader-wrapper small active" id="loginProgress" style="display: none;">
                                    <div class="spinner-layer spinner-green-only">
                                        <div class="circle-clipper left">
                                            <div class="circle"></div>
                                        </div>
                                        <div class="gap-patch">
                                            <div class="circle"></div>
                                        </div>
                                        <div class="circle-clipper right">
                                            <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/materialize.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/auth.js"></script>
</body>

</html>