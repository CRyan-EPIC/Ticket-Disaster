<?php
// File Inclusion - High Security Level
// Requires filename to start with "file" - allows file:// wrapper

$file = $_GET['page'] ?? '';

if (!fnmatch("file*", $file) && $file != "include.php") {
    $file = '';
    echo "<span style='color: #ef4444;'>ERROR: File not found.</span>";
}
