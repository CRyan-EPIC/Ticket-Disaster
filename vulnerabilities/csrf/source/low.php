<?php
// CSRF - Low Security Level
// No CSRF protection, no current password check, uses GET

if (isset($_GET['Change'])) {
    $pass_new  = $_GET['password_new'];
    $pass_conf = $_GET['password_conf'];

    if ($pass_new == $pass_conf) {
        $pass_new = md5($pass_new);

        $conn = dvwaDbConnect();
        $user = dvwaCurrentUser();

        // Vulnerable: no CSRF token, uses GET, no current password required
        $query = "UPDATE users SET password = '$pass_new' WHERE username = '$user';";
        $conn->query($query);
        $conn->close();

        $html .= "<i class='fas fa-check-circle'></i> Password changed successfully.";
    } else {
        $html .= "<i class='fas fa-times-circle'></i> Passwords did not match.";
    }
}
