<?php
// XSS Stored - Impossible Security Level
// Both fields use htmlspecialchars + prepared statements + CSRF token

if (isset($_POST['btnSign'])) {
    if (!checkToken($_POST['user_token'] ?? '', $_SESSION['session_token'] ?? '')) {
        $html .= "<span style='color: #ef4444;'>Invalid CSRF token.</span>";
    } else {
        $message = trim($_POST['mtxMessage']);
        $name    = trim($_POST['txtName']);

        // Proper sanitization
        $message = stripslashes($message);
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $name = stripslashes($name);
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        $conn = dvwaDbConnect();
        $stmt = $conn->prepare("INSERT INTO guestbook (name, comment) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $message);
        $stmt->execute();
        $conn->close();

        $html .= "Review posted successfully!";
    }
    generateSessionToken();
}
