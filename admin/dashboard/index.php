<?php

 require __DIR__.'/../../includes/main.php';
heads();
?>


<main>

    <div class="container">

        <div class="row">
            <div class="col s12 m12 p-0">
                <blockquote>
                    <h5 class="blue-text">Dashboard</h5>
                    <p>Live Server Details</p>
                </blockquote>
            </div>
        </div>

        <!-- Dashboard Start -->
        <div class="row">
            <div class="col s6 m3 white-text tooltipped" data-position="top" data-tooltip="Number of Severs" onclick="location.href='../servers/'">
                <div class="card blue darken-1 ">
                    <div class="card-content white-text">
                        <div class="row">
                            <div class="col s6">
                                <i class="material-icons background-round mt-4" style="font-size: 35px;">dns</i>
                                <!-- <h5>Working</h5> -->
                            </div>
                            <div class="col s6">
                                <h5 class="mb-0 pt-2" id="d1">-</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s6 m3 white-text tooltipped" data-position="top" data-tooltip="Online Severs" onclick="set_server_data(1);">
                <div class="card green darken-1 ">
                    <div class="card-content white-text">
                        <div class="row">
                            <div class="col s6">
                                <i class="material-icons background-round mt-4" style="font-size: 35px;">thumb_up</i>
                                <!-- <h5>Working</h5> -->
                            </div>
                            <div class="col s6">
                                <h5 class="mb-0 pt-2" id="d2">-</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s6 m3 white-text tooltipped" data-position="top" data-tooltip="Offline Severs" onclick="set_server_data(0);">
                <div class="card red darken-1 ">
                    <div class="card-content white-text">
                        <div class="row">
                            <div class="col s6">
                                <i class="material-icons background-round mt-4" style="font-size: 35px;">thumb_down</i>
                                <!-- <h5>Working</h5> -->
                            </div>
                            <div class="col s6">
                                <h5 class="mb-0 pt-2" id="d3">-</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s6 m3 white-text tooltipped" data-position="top" data-tooltip="Last Ping / Tested at" onclick="dashboard();">
                <div class="card yellow darken-1 ">
                    <div class="card-content white-text">
                        <div class="row">
                            <div class="col s6">
                                <i class="material-icons background-round mt-4" style="font-size: 35px;">wifi_protected_setup</i>
                                <!-- <h5>Working</h5> -->
                            </div>
                            <div class="col s6">
                                <h6 class="mb-0 center pt-2" id="d4">-</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dashboard Stop -->
        <!-- Chart Start -->
        <div class="row">
            <div class="col s12 m7 p-0">

                <div class="btn-group btn-group-toggle right" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="radio" name="timeframe_short" id="minute" autocomplete="off" checked> Hour
                    </label>
                    <label class="btn btn-secondary ">
                        <input type="radio" name="timeframe_short" id="hour" autocomplete="off"> Day
                    </label>
                    <label class="btn btn-secondary ">
                        <input type="radio" name="timeframe_short" id="day" autocomplete="off"> Week
                    </label>
                </div>

                <canvas id="history_short">Your browser does not support the canvas element.</canvas>
            </div>
            <div class="col s12 m5">
                <br>
                <br>
                <canvas id="online_report"></canvas>
            </div>
        </div>
        <!-- Chart Stop -->

    </div>

    <div id="server_modal" class="modal">
        <div class="modal-content">
            <h4 id="server_title">Bitsathy Server</h4>

            <div>


                <table>
                    <thead>
                        <tr>
                            <th>Server Name</th>
                            <th>Last Ping check</th>
                            <th>Tools</th>
                        </tr>
                    </thead>
                    <tbody id="server_body" class="highlight">
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">close</a>
        </div>
    </div>
</main>
<?php
footer();
?>