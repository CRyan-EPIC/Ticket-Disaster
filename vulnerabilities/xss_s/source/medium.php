<?php
// XSS Stored - Medium Security Level
// Message is sanitized with htmlspecialchars + strip_tags
// Name only has <script> replaced (vulnerable to other tags)

if (isset($_POST['btnSign'])) {
    $message = trim($_POST['mtxMessage']);
    $name    = trim($_POST['txtName']);

    // Message: properly sanitized
    $message = strip_tags(addslashes($message));
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    // Name: only removes <script> - vulnerable to bypass
    $name = str_replace('<script>', '', $name);
    $name = stripslashes($name);

    $conn = dvwaDbConnect();
    $message = mysqli_real_escape_string($conn, $message);
    $name    = mysqli_real_escape_string($conn, $name);

    $query = "INSERT INTO guestbook (name, comment) VALUES ('$name', '$message')";
    $conn->query($query);
    $conn->close();

    $html .= "Review posted successfully!";
}
