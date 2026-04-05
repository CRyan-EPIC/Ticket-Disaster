<?php
// XSS Stored - High Security Level
// Message is fully encoded, name uses regex to strip <script> but still vulnerable to other tags

if (isset($_POST['btnSign'])) {
    $message = trim($_POST['mtxMessage']);
    $name    = trim($_POST['txtName']);

    // Message: properly sanitized
    $message = strip_tags(addslashes($message));
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    // Name: regex removes script tags but other tags like <img> still work
    $name = preg_replace('/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t/i', '', $name);

    $conn = dvwaDbConnect();
    $message = mysqli_real_escape_string($conn, $message);
    $name    = mysqli_real_escape_string($conn, $name);

    $query = "INSERT INTO guestbook (name, comment) VALUES ('$name', '$message')";
    $conn->query($query);
    $conn->close();

    $html .= "Review posted successfully!";
}
