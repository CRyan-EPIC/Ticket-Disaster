<?php
// Brute Force - Impossible Security Level
// Account lockout after 3 failed attempts, CSRF token, prepared statements

if (isset($_GET['Login'])) {
    if (!checkToken($_GET['user_token'] ?? '', $_SESSION['session_token'] ?? '')) {
        $html .= "<div class='alert alert-danger'>Invalid CSRF token.</div>";
    } else {
        $user = $_GET['username'];
        $pass = md5($_GET['password']);
        $theme = getThemeConfig();

        $conn = dvwaDbConnect();

        // Check for account lockout
        $stmt = $conn->prepare("SELECT failed_login, last_login FROM users WHERE username = ?");
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $result = $stmt->get_result();

        $locked = false;
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($row['failed_login'] >= 3) {
                // Locked for 15 minutes
                if ($row['last_login'] && (time() - strtotime($row['last_login'])) < 900) {
                    $locked = true;
                    $html .= "<div class='alert alert-warning'>";
                    $html .= "<i class='fas fa-lock'></i> Account locked due to too many failed attempts. Try again in 15 minutes.";
                    $html .= "</div>";
                } else {
                    // Reset after lockout period
                    $stmt2 = $conn->prepare("UPDATE users SET failed_login = 0 WHERE username = ?");
                    $stmt2->bind_param('s', $user);
                    $stmt2->execute();
                }
            }
        }

        if (!$locked) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param('ss', $user, $pass);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                // Reset failed login count
                $stmt2 = $conn->prepare("UPDATE users SET failed_login = 0, last_login = NOW() WHERE username = ?");
                $stmt2->bind_param('s', $user);
                $stmt2->execute();

                $html .= "<div class='alert alert-success'>";
                $html .= "<i class='fas fa-check-circle'></i> " . htmlspecialchars($theme['vuln_brute_success']) . ", " . htmlspecialchars($row['first_name']) . "!";
                $html .= "</div>";
            } else {
                sleep(rand(0, 3));
                // Increment failed login
                $stmt2 = $conn->prepare("UPDATE users SET failed_login = failed_login + 1, last_login = NOW() WHERE username = ?");
                $stmt2->bind_param('s', $user);
                $stmt2->execute();

                $html .= "<div class='alert alert-danger'>";
                $html .= "<i class='fas fa-times-circle'></i> Invalid credentials. Access denied.";
                $html .= "</div>";
            }
        }

        $conn->close();
    }
    generateSessionToken();
}
