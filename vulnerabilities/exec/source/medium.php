<?php
// Command Injection - Medium Security Level
// Blacklists && and ; but misses | and ||

if (isset($_POST['Submit'])) {
    $coupon = $_REQUEST['coupon'];
    $file   = dirname(__FILE__) . '/../coupons.txt';

    $substitutions = array(
        '&&' => '',
        ';'  => '',
    );
    $coupon = str_replace(array_keys($substitutions), $substitutions, $coupon);

    // Still vulnerable: | and || are not blocked
    $cmd = shell_exec('cat ' . $file . ' | grep -i ' . $coupon);

    if ($cmd && trim($cmd) !== '') {
        $html .= '<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>Coupon accepted!</strong> Code <code>' . htmlspecialchars(trim($cmd)) . '</code> applied &mdash; 20% off your next ticket purchase.</div>';
    } else {
        $html .= '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Coupon not accepted.</strong> The code you entered is invalid or expired.</div>';
    }
}
