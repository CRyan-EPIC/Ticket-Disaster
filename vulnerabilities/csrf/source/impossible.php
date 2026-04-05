<?php
// CSRF - Impossible Security Level
// Requires current password + CSRF token + prepared statement

if (isset($_GET['Change'])) {
    if (!checkToken($_GET['user_token'] ?? '', $_SESSION['session_token'] ?? '')) {
        $html .= "<i class='fas fa-times-circle'></i> Invalid CSRF token.";
    } else {
        $pass_curr = $_GET['password_current'] ?? '';
        $pass_new  = $_GET['password_new'];
        $pass_conf = $_GET['password_conf'];

        // Verify current password
        $conn = dvwaDbConnect();
        $user = dvwaCurrentUser();
        $pass_curr_hash = md5($pass_curr);

        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param('ss', $user, $pass_curr_hash);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            if ($pass_new == $pass_conf) {
                $pass_new_hash = md5($pass_new);
                $stmt2 = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $stmt2->bind_param('ss', $pass_new_hash, $user);
                $stmt2->execute();
                $html .= "<i class='fas fa-check-circle'></i> Password changed successfully.";
            } else {
                $html .= "<i class='fas fa-times-circle'></i> New passwords did not match.";
            }
        } else {
            $html .= "<i class='fas fa-times-circle'></i> Current password is incorrect.";
        }
        $conn->close();
    }
    generateSessionToken();
}
