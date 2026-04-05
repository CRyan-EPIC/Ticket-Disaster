<?php
/**
 * Quick theme switcher handler (topbar dropdown).
 * Sets the new theme, re-seeds concerts, redirects back.
 */
session_start();
require_once __DIR__ . '/config/config.inc.php';
require_once __DIR__ . '/config/themes.php';
require_once __DIR__ . '/dvwa/includes/dvwaPage.inc.php';

if (isset($_POST['theme']) && isset($GLOBALS['THEMES'][$_POST['theme']])) {
    $newTheme = $_POST['theme'];
    $oldTheme = getCurrentTheme();

    setTheme($newTheme);

    // Only re-seed if theme actually changed
    if ($newTheme !== $oldTheme) {
        dvwaReseedForTheme($newTheme);
    }
}

// Redirect back to where the user was
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $referer);
exit;
