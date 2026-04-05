<?php
// File Inclusion - Medium Security Level
// Removes common traversal/remote patterns (bypassable with double encoding)

$file = $_GET['page'] ?? '';
$file = str_replace(array("http://", "https://", "../", "..\\"), "", $file);
