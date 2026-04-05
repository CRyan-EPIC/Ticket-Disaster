<?php
// Brute Force - High Security Level
// Random delay + anti-CSRF token

if (isset($_GET['Login'])) {
    if (!checkToken($_GET['user_token'] ?? '', $_SESSION['session_token'] ?? '')) {
        $html .= "<div class='alert alert-danger'>Invalid CSRF token.</div>";
    } else {
        $user = $_GET['username'];
        $pass = md5($_GET['password']);
        $theme = getThemeConfig();

        $conn = dvwaDbConnect();
        $user = mysqli_real_escape_string($conn, $user);

        $query = "SELECT * FROM `users` WHERE username = '$user' AND password = '$pass';";
        $result = $conn->query($query);

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $html .= "<div class='alert alert-success'>";
            $html .= "<i class='fas fa-check-circle'></i> " . htmlspecialchars($theme['vuln_brute_success']) . ", {$row['first_name']}!";
            $html .= "</div>";
        } else {
            sleep(rand(0, 3));
            $html .= "<div class='alert alert-danger'>";
            $html .= "<i class='fas fa-times-circle'></i> Invalid credentials. Access denied.";
            $html .= "</div>";
        }

        $conn->close();
    }
    generateSessionToken();
}
