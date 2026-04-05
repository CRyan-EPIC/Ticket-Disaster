<?php
// Brute Force - Low Security Level
// No rate limiting, no lockout, also SQLi vulnerable

if (isset($_GET['Login'])) {
    $user = $_GET['username'];
    $pass = $_GET['password'];
    $pass = md5($pass);

    $conn = dvwaDbConnect();
    $theme = getThemeConfig();

    // Vulnerable: no rate limiting AND SQL injection on $user
    $query = "SELECT * FROM `users` WHERE username = '$user' AND password = '$pass';";
    $result = $conn->query($query);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $html .= "<div class='alert alert-success'>";
        $html .= "<i class='fas fa-check-circle'></i> " . htmlspecialchars($theme['vuln_brute_success']) . ", {$row['first_name']}!";
        $html .= "<br><small>" . htmlspecialchars($theme['vuln_brute_flavor']) . "</small>";
        $html .= "</div>";
    } else {
        $html .= "<div class='alert alert-danger'>";
        $html .= "<i class='fas fa-times-circle'></i> Invalid credentials. Access denied.";
        $html .= "</div>";
    }

    $conn->close();
}
