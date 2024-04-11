<?php
// Define the reset_login function within this context
function reset_login($login) {
    $db = new CRUD;
    $data = $db->select('SELECT email, user_id, name FROM user_master WHERE user_name = ?', [$login]);

    // Check if user exists
    if (!empty($data) && isset($data[0]['user_id']) && $data[0]['user_id']) {
        $salt = salt();  // Securely generate a salt or key for the password reset.
        $resetInsertion = $db->inserts('INSERT INTO password_reset(email, key_auth) VALUES (?, ?)', [$data[0]['email'], $salt]);

        if ($resetInsertion) {
            $email = $data[0]['email'];
            $resetUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/auth/reset/?key=' . $salt . '&email=' . $email . '&action=reset';
            $message = "Hello " . $data[0]['name'] . ",<br>You recently requested to reset your password for your PHP Server monitor.<br>Use the below link to reset it. This password reset is only valid for the next 24 hours.<br>URL: " . $resetUrl;
            
            // Send the email with the password reset link
            send_mail('Reset Password Link!', $message, [$email]);
            complete('Reset link was sent to your mail id!');
        } else {
            err('Error processing password reset.');
        }
    } else {
        err('Invalid User Name');
    }
}