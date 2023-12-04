<?php
require_once __DIR__ . '/utils/init.php';

$db = new CRUD;

$datas = $db->select("SELECT *,url ip FROM server_master WHERE state=1", []);

$final=array();


foreach ($datas as $key => $data) {
    extract($data);
    // echo $ip;
    if($type=='ping'){
       $result=ip_ping($ip, $time_out);
        if($result>0){
            server_update($db,$server_id,1,'Ping Successfully');
            $final[]="($server_id,1,$result)";
        }else{
            server_update($db,$server_id,0,'Ping Failed');
            $final[]="($server_id,0,$result)";
        }
    }
    if($type=='service'){
        $result=service_ping($ip, $port, $time_out);
            if($result>0){
                server_update($db,$server_id,0,'Successfully.');

                $final[]="($server_id,1,$result)";
            }else{
                server_update($db,$server_id,0,'A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond.');
                $final[]="($server_id,0,$result)";
            }
    }
    if($type=='website'){
    $result=website(
        $db,
        $ip,
        true,
        ($method == '' ? false : true),
        $timeout,
        true,
        $user_name,
        $user_pass,
        $method,
        $post_field,
        $server_id,
        $redirect_type,
        $ssl
    );
        if($result==0){
            $final[]="($server_id,0,$result)";
        }else{
            $final[]="($server_id,1,$result)";
        }
    }
}
$db->inserts("INSERT INTO server_ping_log(server_id,state,latency) values".implode(',',$final),[]);



$datas = $db->select("SELECT * FROM server_master WHERE live=0 and state=1", []);

if(sizeof($datas)==0){
    die("Good Bye !!");
}

$_t='<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

<h5 style="text-align:center;">List of severs offline</h5>
<br>
<br>
<br>
<table>
<tr>
  <th>Server Name</th>
  <th>Ip / Url</th>
  <th>Last Online</th>
  <th>Last Ping Check</th>
</tr>
';
$_m=$_t;
$__m=0;
$__t=0;

foreach ($datas as $key => $data) {
if($data['telegram']){
$_t.='<tr>
<td>'.$data['server_name'].'</td>
<td>'.$data['ip'].'</td>
<td>'.$data['last_online'].'</td>
<td>'.$data['last_offline'].'</td>
</tr>';
$__t=1;
}

if($data['email']){
$_m.='<tr>
<td>'.$data['server_name'].'</td>
<td>'.$data['ip'].'</td>
<td>'.$data['last_online'].'</td>
<td>'.$data['last_offline'].'</td>
</tr>';
$__m=1;
}
}
$_t.='</table>';
$_m.='</table>';
if($__m){
    send_mail_warn("List of servers Offline !", $_m);
}
// if($__t){
//     send_telegram_warn($_t);
// }
