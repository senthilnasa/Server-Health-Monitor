<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../includes/init.php';


if(($_SESSION['islogin']) && (!$_SESSION['islogin'])){
    err('Login Required');
}

check('fun', 'Type Required');
extract($_POST);

switch ($fun) {

    // Dashboards
        case 'dashboard_data':
            $data=dashboard_data();
        break;

        case 'dashboard_chart_online':
            $data=dashboard_chart_online();
        break; 

        case 'dashboard_chart_offline':
            $data=dashboard_chart_offline();
        break;    

        case 'dashboard_live':
            extract($_POST);
            if($typ==0){

                $data=servers_dash_list(0);
            }
            else if($typ==1){
                $data=servers_dash_list(1);
            }
            else{
                err("Invalid request");
                die();
            }
        break;


    //User Manager List
        //User list
        case 'users_list':
            $data=users_list();
        break; 
        //User update
        case 'user_update':
            check('user_id', 'user_id Required');
            check('mail', 'mail Required');
            check('name', 'name Required');
            check('tid', 'tid Required');
            extract($_POST);
            $data=user_update($user_id,$mail,$name,$tid);
        break;  
         //Delete User
        case 'user_delete':
            check('user_id', 'user_id Required');
            extract($_POST);
            $data=user_delete($user_id);
        break; 


        // user_add
        case 'user_add':
            check('mail', 'mail Required');
            check('name', 'name Required');
            check('uname', 'name Required');
            check('tid', 'tid Required');
            check('pass', 'pass Required');
            extract($_POST);
            $data=user_add($uname,$mail,$name,$tid,$pass);
        break; 
        

    //Server Managemnet
        case 'server_list':
            $data=server_list();
        break;   
       
    //login_log
    case 'login_log':
        $data=login_log();
    break; 

    //notification_log
    case 'notification_log':
        $data=notification_log();
    break; 
    // server_details
    case 'server_details':
        check('sid', 'sid Required');
        extract($_POST);
        $data=server_details($sid);
    break; 
        // SErver Offile

    case 'server_off':
        check('sid', 'sid Required');
        extract($_POST);
        $data=server_offline($sid);
    break; 
        //Server Onnline
    case 'server_on':
        check('sid', 'sid Required');
        extract($_POST);
        $data=server_online($sid); 
     break; 
    //  latency
    case 'server_latency':
        check('sid', 'sid Required');
        extract($_POST);
        $data=server_latency($sid); 
     break; 
    //  server_report
    case 'server_report':
        check('sid', 'sid Required');
        extract($_POST);
        $data=server_report($sid); 
     break; 

    // Server Addd
    case 'server_add':
        check('type', 'type Required');

        if($type=="ping"){
        check('server_name', 'server_name Required');
        check('url', 'url Required');
        check('type', 'type Required');
        check('time_out', 'time_out Required');
        check('telegram', 'telegram Required',true);
        check('state', 'state Required',true);
        check('email', 'email Required',true);
        check('threshold', 'threshold Required',true);
        extract($_POST);
        
        $data=server_add($server_name,$url,$type,$telegram,$state,$email,$threshold,$time_out); 
        }
        if($type=="service"){
            check('server_name', 'server_name Required');
            check('url', 'url Required');
            check('type', 'type Required');
            check('time_out', 'time_out Required');
            check('port', 'port Required',true);
            check('telegram', 'telegram Required',true);
            check('email', 'email Required',true);
            check('state', 'state Required',true);
            check('threshold', 'threshold Required',true);
            extract($_POST);

            $data=server_add_s($server_name,$url,$type,$telegram,$state,$email,$threshold,$time_out,$port); 

        }
        if($type=="website"){
            // complete($_POST);
            check('server_name', 'server_name Required');
            check('url', 'url Required');
            check('type', 'type Required');
            check('time_out', 'time_out Required');
            check('method', 'method Required');
            check('post_field', 'post_field Required',true);
            check('ssl', 'ssl Required',true);
            check('header_name', 'header_name Required',true);
            check('header_value', 'header_value Required',true);
            check('telegram', 'telegram Required',true);
            check('email', 'email Required',true);
            check('state', 'state Required',true);
            check('threshold', 'threshold Required',true);
            check('user_name', 'User Name Required',true);
            check('user_pass', 'User Password Required',true);
            

            check('redirect_type', 'redirect_type Required',true);
        extract($_POST);

            $data=server_add_w($server_name,$url,$type,$telegram,$state,$email,$threshold,$time_out,$method,$post_field,$header_name,$header_value,$redirect_type,$ssl,$user_name,$user_pass); 

        }
     break; 

     case 'server_edit':
        check('type', 'type Required');
        check('sid', 'sid Required');


            if($type=="ping"){
            check('server_name', 'server_name Required');
            check('url', 'url Required');
            check('type', 'type Required');
            check('time_out', 'time_out Required');
            check('telegram', 'telegram Required',true);
            check('state', 'state Required',true);
            check('email', 'email Required',true);
            check('threshold', 'threshold Required',true);
            extract($_POST);
            // complete($_POST);

            $data=server_update($sid, $server_name, $url, $type, $telegram, $state, $email,$threshold,$time_out); 
            }
            if($type=="service"){
                check('server_name', 'server_name Required');
                check('url', 'url Required');
                check('type', 'type Required');
                check('time_out', 'time_out Required');
                check('port', 'port Required',true);
                check('telegram', 'telegram Required',true);
                check('email', 'email Required',true);
                check('state', 'state Required',true);
                check('threshold', 'threshold Required',true);
                extract($_POST);
                $data=server_update_s($sid, $server_name, $url, $type, $telegram, $state, $email,$threshold,$time_out,$port); 
    
            }
            if($type=="website"){
                // complete($_POST);
                check('server_name', 'server_name Required');
                check('url', 'url Required');
                check('type', 'type Required');
                check('time_out', 'time_out Required');
                check('method', 'method Required');
                check('post_field', 'post_field Required',true);
                check('ssl', 'ssl Required',true);
                check('header_name', 'header_name Required',true);
                check('header_value', 'header_value Required',true);
                check('telegram', 'telegram Required',true);
                check('email', 'email Required',true);
                check('state', 'state Required',true);
                check('threshold', 'threshold Required',true);
                check('user_name', 'User Name Required',true);
                check('user_pass', 'User Password Required',true);
                
    
                check('redirect_type', 'redirect_type Required',true);
                extract($_POST);
    
                $data=server_update_w($sid,$server_name,$url,$type,$telegram,$state,$email,$threshold,$time_out,$method,$post_field,$header_name,$header_value,$user_name,$user_pass,$redirect_type,$ssl); 
    
            }

         break; 

        case 'server_delete':
        extract($_POST);
        $data=server_delete($sid); 
        break;
  
    //Default
    default:
       err('Invalid request');
}


complete($data);
