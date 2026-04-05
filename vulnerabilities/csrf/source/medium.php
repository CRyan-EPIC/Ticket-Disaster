<?php
// CSRF - Medium Security Level
// Checks HTTP Referer header

if (isset($_GET['Change'])) {
    // Check Referer - can be bypassed
    if (stripos($_SERVER['HTTP_REFERER'] ?? '', $_SERVER['SERVER_NAME']) !== false) {
        $pass_new  = $_GET['password_new'];
        $pass_conf = $_GET['password_conf'];

        if ($pass_new == $pass_conf) {
            $pass_new = md5($pass_new);
            $conn = dvwaDbConnect();
            $user = dvwaCurrentUser();
            $query = "UPDATE users SET password = '$pass_new' WHERE username = '$user';";
            $conn->query($query);
            $conn->close();
            $html .= "<i class='fas fa-check-circle'></i> Password changed successfully.";
        } else {
            $html .= "<i class='fas fa-times-circle'></i> Passwords did not match.";
        }
    } else {
        $html .= "<i class='fas fa-times-circle'></i> Request did not originate from this site.";
    }
}
