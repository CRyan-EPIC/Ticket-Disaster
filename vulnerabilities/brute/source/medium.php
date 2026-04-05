<?php
// Brute Force - Medium Security Level
// Adds 2-second delay on failed login

if (isset($_GET['Login'])) {
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
        // Slow down brute force attempts
        sleep(2);
        $html .= "<div class='alert alert-danger'>";
        $html .= "<i class='fas fa-times-circle'></i> Invalid credentials. Access denied.";
        $html .= "</div>";
    }

    $conn->close();
}
