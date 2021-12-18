<?php

 require __DIR__.'/../../includes/main.php';
heads();
?>


<main>
    <div class="row">
        <div class="col s12 m12 p-0">
            <blockquote>
                <h5 class="blue-text">Users Management</h5>
                <p>List of Users</p>
            </blockquote>
        </div>
        <a data-target="modal1" class="btn modal-trigger right" href="javascript:addUser();">Add User</a>
        <br>
    </div>
    <div class="container center-align">
        <table id="serverList" class="responsive-table highlight centered  ml-2">
            <thead style="display: none;">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>User Name</th>
                    <th>Telegram Id</th>
                    <th>Mail id</th>
                    <th>Join at</th>
                    <th>Tool</th>
                </tr>
            </thead>

            <tbody style="display: none;">

            </tbody>
        </table>

    </div>
    </div>
</main>
<div id="edit_modal" class="modal">
    <div class="modal-content">
        <h5 id="edit_tit">Update User</h5>
        <br>
        <div class="row">
            <form class="col s12" id="edit_form">
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="Nick Name" id="edit_name" type="text" class="validate">
                        <label for="edit_name">Nick Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="edit_email_inline" type="email" class="validate">
                        <label for="edit_email_inline">Email</label>
                        <span class="helper-text" data-error="wrong" data-success="right"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="Telegram Id" id="edit_tid" type="text" class="validate">
                        <label for="edit_tid">Telegram Id</label>
                    </div>
                </div>
                <div class="row">
                    <button class="btn waves-effect waves-light" type="submit" name="action" id="edit_button">Submit
                    </button>
                </div>
            </form>

        </div>
    </div>

    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
</div>

<div id="creat_modal" class="modal">
    <div class="modal-content">
        <h5>Add User</h5>
        <br>
        <div class="row">
            <form class="col s12" id="creat_form">
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="Nick Name" id="creat_name" type="text" required class="validate">
                        <label for="creat_name">Nick Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="User Name" id="creat_uname" type="text" required class="validate">
                        <label for="creat_name">User Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="creat_email_inline" type="email" required class="validate">
                        <label for="creat_email_inline">Email</label>
                        <span class="helper-text" data-error="wrong" data-success="right"></span>
                    </div>
                </div>
                <div id="pass_div">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="password" type="password" class="validate">
                            <label for="password">Password</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="passwordConfirm" type="password">
                            <label id="lblPasswordConfirm" for="passwordConfirm" data-error="Password not match" data-success="Password Match">Password (Confirm)</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="Telegram Id" value="-" id="creat_tid" required type="text" class="validate">
                        <label for="creat_tid">Telegram Id</label>
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