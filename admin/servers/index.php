<?php

require '../../includes/main.php';
heads();
?>


<main>
    <div class="row">
        <div class="col s12 m12 p-0">
            <blockquote>
                <h5 class="blue-text">Sever Management</h5>
                <p>List of Servers</p>
            </blockquote>
        </div>
    </div>
    <div class="container center-align">
        <a class="btn-floating waves-effect waves-light btn right tooltipped modal-trigger" href="#edit_server" data-position="top" data-tooltip="Add server details"><i class="material-icons">add</i></a>
        <table id="serverList" class="responsive-table highlight centered  ml-2">
            <thead style="display: none;">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Ip</th>
                    <th>Type</th>
                    <th>Last Offline</th>
                    <th>Last Online</th>
                    <th>Latency</th>
                    <th>Server Ping Check</th>
                    <th>Tool</th>
                </tr>
            </thead>

            <tbody style="display: none;">

            </tbody>
        </table>

    </div>
    </div>
</main>

<div id="edit_server" class="modal">
    <div class="modal-content">
        <h5>Add Server</h5>
        <br>
        <div class="row">
            <form class="col s12" id="edit_form">
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="Server Name" name="server_name" type="text" required class="validate">
                        <label >Server Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="Ip/Url" name="url" type="text" required class="validate">
                        <label >Ip/Url</label>
                    </div>
                </div>
                <div class="row">

                    <div class="input-field col s12">
                        <select required class="validate" name="type" onchange="ser_typ()">
                            <option value="ping">Ping</option>
                            <option value="service">Service</option>
                            <option value="website">Website</option>
                        </select>
                        <label>Choose server type</label>
                    </div>

                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="Time out" name="time_out" type="text" value="10" onkeypress="return onlyNumberKey(event)" required class="validate">
                        <label >Time out</label>
                    </div>
                </div>



                <div class="row" id="port">
                    <div class="input-field col s12">
                        <input placeholder="Port" name="port" type="text" value="80" onkeypress="return onlyNumberKey(event)" required class="validate">
                        <label >Port</label>
                    </div>
                </div>

                <div id="web1">
                    <div class="row">
                        <div class="input-field col s12">
                            <input placeholder="SSL Exipry Alert" name="ssl" type="text" value="0" onkeypress="return onlyNumberKey(event)" required class="validate">
                            <label >SSL Exipry Alert date "Use 0 to disable check."</label>
                        </div>
                    </div>
                    <div class="row">
                        <p>Request method</p>

                        <div class="input-field col s12">
                            <select required class="browser-default" name="method">
                                <option value="GET">GET</option>
                                <option value="HEAD">HEAD</option>
                                <option value="POST">POST</option>
                                <option value="PUT">PUT</option>
                                <option value="DELETE">DELETE</option>
                                <option value="CONNECT">CONNECT</option>
                                <option value="OPTIONS">OPTIONS</option>
                                <option value="TRACE">TRACE</option>
                                <option value="PATCH">PATCH</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input placeholder="param1=val1&amp;param2=val2&amp;..." name="post_field" type="text" class="validate">
                            <label >Post field</label>
                        </div>
                    </div>

                    <div class="row">
                        <p>Redirecting to another domain</p>
                        <div class="input-field col s12">
                            <select class="browser-default" name="redirect_type">
                                <option value="1">ok</option>
                                <option value="0">bad</option>
                            </select>
                        </div>
                    </div>

                    <p>Authentication Settings (Optional)</p>
                    <div class="row">
                        <div class="input-field col s12">
                            <input placeholder="Header name" name="header_name" type="text" class="validate">
                            <label >Header name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input placeholder="Header value" name="header_value" type="text" class="validate">
                            <label >Header value</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input placeholder="User Name" name="user_name" type="text" class="validate">
                            <label >User Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input placeholder="User Password" name="user_pass" type="text" class="validate">
                            <label >User Password</label>
                        </div>
                    </div>
                </div>
                <div class="row" id="port">
                    <div class="input-field col s12">
                        <input placeholder="Warning threshold" name="threshold" type="text" value="5" onkeypress="return onlyNumberKey(event)" required class="validate">
                        <label >Warning threshold</label>
                        <p>Number of failed checks required before it is marked offline.</p>
                    </div>
                </div>



                <p>Notification</p>
                <div class="row">
                    <div class="input-field col s12">
                        <select required class="validate" name="telegram">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                        <label>Telegram Notification</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select required class="validate" name="email">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                        <label>Email Notification</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select required class="validate" name="state">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                        <label>Ping Check</label>
                    </div>
                </div>
                <div class="row">
                    <button class="btn waves-effect waves-light" type="submit" name="action" id="creat_button">Submit
                    </button>
                </div>
            </form>

        </div>
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
</div>
<?php
footer();
?>