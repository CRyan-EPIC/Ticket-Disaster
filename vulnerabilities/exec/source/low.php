<?php
// Command Injection - Low Security Level
// Coupon code passed directly to grep via shell_exec — no sanitization

if (isset($_POST['Submit'])) {
    $coupon = $_REQUEST['coupon'];
    $file   = dirname(__FILE__) . '/../coupons.txt';

    // Vulnerable: user input appended directly to shell command.
    // 'cat FILE | grep -i COUPON' puts the file path before the injection point
    // so '; whoami', '; ls', '| cat /etc/passwd' etc. all work cleanly.
    $cmd = shell_exec('cat ' . $file . ' | grep -i ' . $coupon);

    if ($cmd && trim($cmd) !== '') {
        $html .= '<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>Coupon accepted!</strong> Code <code>' . htmlspecialchars(trim($cmd)) . '</code> applied &mdash; 20% off your next ticket purchase.</div>';
    } else {
        $html .= '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Coupon not accepted.</strong> The code you entered is invalid or expired.</div>';
    }
}
