<?php
// XSS Stored - Low Security Level
// SQL-escaped for database storage but NO HTML encoding on output

if (isset($_POST['btnSign'])) {
    $message = trim($_POST['mtxMessage']);
    $name    = trim($_POST['txtName']);

    // Basic sanitization for SQL only - NOT for HTML output
    $message = stripslashes($message);
    $name    = stripslashes($name);

    $conn = dvwaDbConnect();
    $message = mysqli_real_escape_string($conn, $message);
    $name    = mysqli_real_escape_string($conn, $name);

    // Vulnerable: data stored and later displayed without HTML encoding
    $query = "INSERT INTO guestbook (name, comment) VALUES ('$name', '$message')";
    $conn->query($query);
    $conn->close();

    $html .= "Review posted successfully!";
}
