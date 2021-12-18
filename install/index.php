<?php
require_once __DIR__ . "/check.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Server Health Monitor | Login </title>
    <link type="text/css" rel="stylesheet" href="../assets/fonts/material-icon/material-icon.css">
    <link type="text/css" rel="stylesheet" href="../assets/css/materialize.min.css" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="../assets/css/style.css" />
    <link type="text/css" rel="stylesheet" href="../assets/css/plane.css" />
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
                        <div id="configExtensionDiv">
                            <p align="left">Welcome to the installation of PHP Server Monitor. This page will guide you through the steps to install or upgrade your monitor.
                                <br>
                                <br>
                                Before we start, we need to verify your system meets the requirements. If you see any errors in the list below, you may still continue, but PHP Server Monitor may not work correctly. It is recommended you fix any errors before continuing.
                                <br>
                                <br>
                            </p>
                            <ul class="collection with-header">
                                <li class="collection-header">
                                    <b>Application Requirements</b>
                                </li>
                                <?php
                                foreach ($sucess as $value) {
                                    echo '<li align="left" class="collection-item"><div>' . $value . '<a href="#!" class="secondary-content"><i class="material-icons ">done_outline</i></a></div></li>';
                                }

                                foreach ($errors as $value) {
                                    echo '<li align="left"  class="collection-item "><div>' . $value . '<a href="#!" class="secondary-content"><i class="material-icons icon-red">dangerous</i></a></div></li>';
                                }
                                ?>
                            </ul>
                            <?php
                            if (count($errors) == 0) {

                            ?>
                                <button onclick="changeConfigView();" class="waves-effect waves-light btn"><i class="material-icons right">double_arrow</i>Let`s Go !</button>
                        </div>


                        <div id="configFileDiv" style="display: none;">
                            <p align="left">Your database information is valid, however we are unable to create the configuration file automatically. Please create a new file in the project directory called "config.php" and copy the information below.
                                <br>
                                <br>
                                After you have copied the configuration, press the button to continue.
                                <br>
                                <br>
                            </p>
                            <textarea id="config" style="height: 900px;resize: none; overflow: hidden; max-height: 250px;" readonly></textarea>

                        </div>


                        <div class="preloader-wrapper small active" id="Progress" style="display: none;">
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

                        <div id="configDiv" style="display: none;">
                            <h5 class="center"><b>DB Config</b></h5>

                            <div class="row center">

                                <div class="input-field col s12">
                                    <i class="material-icons prefix">link</i>
                                    <input id="d1" autocomplete="off" type="text" class="validate" required>
                                    <label for="d1">Host Name</label>
                                </div>

                                <div class="input-field col s12">
                                    <i class="material-icons prefix">stream</i>
                                    <input id="d2" autocomplete="off" type="text" class="validate" required value="3306">
                                    <label for="d2">Database Port</label>
                                </div>

                                <div class="input-field col s12">
                                    <i class="material-icons prefix">person_outlined</i>
                                    <input id="d3" autocomplete="off" type="text" class="validate" required>
                                    <label for="d3">User Name</label>
                                </div>

                                <div class="input-field col s12">
                                    <i class="material-icons prefix">lock_outlined</i>
                                    <input id="d4" type="password" class="validate" required>
                                    <label for="d4">Password</label>
                                </div>

                                <div class="input-field col s12">
                                    <i class="material-icons prefix">storage_outlined</i>
                                    <input id="d5" type="text" class="validate" required>
                                    <label for="d5">Database Name</label>
                                </div>

                                <div class="input-field col s12 center">
                                    <button class="btn waves-effect waves-light" onclick="verfiyDbConfig()">Vefiry
                                        <i class="material-icons right">exit_to_app</i>
                                    </button>

                                </div>
                            </div>
                        </div>

                        <div id="configAdminUserDiv" style="display: none;">
                            <p>
                                Sweet, your database connection is up and running!
                                <br>
                                <br>
                                Next, please set up a new account to access your monitor:

                                <br>
                                <br>
                                <br>
                            </p>
                            <h5 class="center"><b>Administrator</b></h5>

                            <div class="row center">


                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">person_outlined</i>
                                        <input id="u5" autocomplete="off" value="Senthil Prabhu" type="text" class="validate" required>
                                        <label for="u5">Your Name</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">person_outlined</i>
                                        <input id="u1" autocomplete="off" value="root" type="text" class="validate" required>
                                        <label for="u1">User Name</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">lock_outlined</i>
                                        <input id="u2" type="password" value="Root@123$" class="validate" required>
                                        <label for="u2">Password</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">lock_outlined</i>
                                        <input id="u3" type="password" value="Root@123$" class="validate" required>
                                        <label for="u3">Password Repeat</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">lock_outlined</i>
                                        <input id="u4" type="email" value="nasa@ma.com" class="validate" required>
                                        <label for="u4">Email Id</label>
                                    </div>

                                    <div class="input-field col s12 center">
                                        <button class="btn waves-effect waves-light" id="loginSubmit" onclick="configAdminUser()">Vefiry
                                            <i class="material-icons right">exit_to_app</i>
                                        </button>
                                    </div>


                            </div>
                        </div>

                    <?php
                            } else {

                                echo '<a class="waves-effect waves-light btn"><i class="material-icons right">double_arrow</i>Let`s Go !</a>';
                            }
                    ?>

                    </div>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/materialize.min.js"></script>
        <script src="../assets/js/script.js"></script>
        <script src="../assets/js/install.js"></script>

</html>