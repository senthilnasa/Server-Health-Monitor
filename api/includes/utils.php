<?php

/**
 * Gets the current date/time in specified format.
 *
 * @param bool $dateOnly Whether to return only the date part.
 * @return string The current date or date-time.
 */
function theDateTime(bool $dateOnly = false): string
{
    // Return either the date or the date-time based on $dateOnly
    return $dateOnly ? date("Y-m-d") : date("Y-m-d H:i:s");
}

/**
 * Retrieves the client IP address considering different server variables.
 *
 * @return string|null The detected IP address or null if not detected.
 */
function getUserIP(): ?string
{
    // Check for IP address set by Cloudflare
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }

    // Prioritize different sources for IP, considering potential proxies
    $client = $_SERVER['HTTP_CLIENT_IP'] ?? null;
    $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
    $remote = $_SERVER['REMOTE_ADDR'] ?? null;

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        return $client;
    }

    if (filter_var($forward, FILTER_VALIDATE_IP)) {
        return $forward;
    }

    return $remote ? $remote : null;
}

/**
 * Generates a random string of a specified length.
 *
 * @param int $length The length of the random string.
 * @return string The generated random string.
 */
function salt(int $length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}

/**
 * Converts a Unix timestamp to a human-readable time difference.
 *
 * @param int $time_ago The past timestamp to compare with the current time.
 * @return string Human-readable time difference.
 */
function timeAgo($time_ago)
{
// $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
// Seconds
    if ($seconds <= 60) {
        return "just now";
    }
//Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    }
//Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hour ago";
        } else {
            return "$hours hrs ago";
        }
    }
//Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "yesterday";
        } else {
            return "$days days ago";
        }
    }
//Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "a week ago";
        } else {
            return "$weeks weeks ago";
        }
    }
//Months
    else if ($months <= 12) {
        if ($months == 1) {
            return "a month ago";
        } else {
            return "$months months ago";
        }
    }
//Years
    else {
        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}
