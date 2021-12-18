<?php
 require __DIR__.'/../../includes/main.php';
heads();
?>


<main>
    <div class="row">
                <div class="col s12 m12 p-0">
                    <blockquote>
                        <h5 class="blue-text">Login Log</h5>
                        <p>List of your logins</p>
                    </blockquote>
                </div>
        </div>
        <div class="container center-align">
        <table id="serverList" class="responsive-table highlight centered  ml-2">
                <thead style="display: none;">
                    <tr>
                        <th>Timestamp</th>
                        <th>Ip</th>
                        <th>State</th>
                    </tr>
                </thead>

                <tbody style="display: none;">

                </tbody>
            </table>
           
        </div>  
    </div>
</main>
<?php
footer();
?>