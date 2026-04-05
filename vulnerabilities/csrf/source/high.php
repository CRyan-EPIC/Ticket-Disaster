<?php
// CSRF - High Security Level
// Uses anti-CSRF token (but can be stolen via XSS)

if (isset($_GET['Change'])) {
    if (!checkToken($_GET['user_token'] ?? '', $_SESSION['session_token'] ?? '')) {
        $html .= "<i class='fas fa-times-circle'></i> Invalid CSRF token.";
    } else {
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
    }
    generateSessionToken();
}
