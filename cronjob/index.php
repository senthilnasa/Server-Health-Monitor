<?php

require_once __DIR__ . '/utils/init.php';
require_once __DIR__ . '/utils/notification.php';

$db = new CRUD;

$servers = $db->select("SELECT *, url AS ip FROM server_master WHERE state = 1", []);

$pingResults = [];
$emailMessage = "<h1>Server Status Update</h1>";
$emailMessage .= "<table border='1'><tr><th>Server IP</th><th>Type</th><th>Status</th><th>Message</th></tr>";

$telegramMessage = "*Server Status Update*\n";
$telegramMessage .= "```\n"; // Start of the monospace block
$telegramMessage .= "Server IP     | Type    | Status  | Message\n";

foreach ($servers as $server) {
    $serverId = $server['server_id'];
    $ip = $server['ip'];
    $type = $server['type'];
    $timeout = $server['time_out'];
    $currentState = $server['state'];

    $newState = $currentState;  // Assume no change to start with
    $stateMessage = "";
    $latency = null;

    switch ($type) {
        case 'ping':
            $latency = ip_ping($ip, $timeout);
            $newState = $latency > 0 ? 1 : 0;
            $stateMessage = $newState ? "Successful" : "Failed";
            break;

        case 'service':
            $port = $server['port'];
            $latency = service_ping($ip, $port, $timeout);
            $newState = $latency > 0 ? 1 : 0;
            $stateMessage = $newState ? "Successful" : "Failed";
            break;

        case 'website':
            // Assuming website_check() is properly defined elsewhere
            $latency=website_check( $db, $ip, true, ($method=='' ? false : true), $timeout, true, $user_name, $user_pass, $method, $post_field, $server_id, $redirect_type, $ssl);
            $newState = $latency !== null && $latency >= 0 ? 1 : 0;
            $stateMessage = $newState ? "Passed" : "Failed";
            break;
    }

    if ($newState != $currentState) {
        server_update($db, $serverId, $newState, $stateMessage, $latency);
        $pingResults[] = "('$serverId', '$newState', '$latency')";

        // Update email content
        $emailMessage .= "<tr><td>$ip</td><td>$type</td><td>" . ($newState ? 'Operational' : 'Down') . "</td><td>$stateMessage</td></tr>";

        // Update Telegram content
        $telegramMessage .= str_pad($ip, 15) . "| " . str_pad($type, 8) . "| " . str_pad(($newState ? 'Operational' : 'Down'), 8) . "| $stateMessage\n";
    }
}

$emailMessage .= "</table>";
$telegramMessage .= "```"; // End of the monospace block

// Insert ping results into the database
if (!empty($pingResults)) {
    $db->insert("INSERT INTO server_ping_log (server_id, state, latency) VALUES " . implode(',', $pingResults), []);
}

// Send the email and Telegram messages if there are updates
if (!empty($pingResults)) {
    echo "Sending notifications...\n";
    
    send_mail($db,"Server Status Update", $emailMessage); // Function send_mail() needs to be defined or replaced
    send_telegram_message($db,$telegramMessage);  // Ensure send_telegram_message() is correctly defined
}


// Echo Current Time
echo "Good Bye!". getCurrentTime();