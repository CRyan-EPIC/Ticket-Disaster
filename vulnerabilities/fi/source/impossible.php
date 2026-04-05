<?php
// File Inclusion - Impossible Security Level
// Whitelist of allowed files

$file = $_GET['page'] ?? '';

$allowed = array('refund-policy.php', 'terms.php', 'faq.php');

if (!in_array($file, $allowed)) {
    $file = '';
    if (isset($_GET['page'])) {
        echo "<span style='color: #ef4444;'>ERROR: File not in whitelist.</span>";
    }
}
