<?php
// Command Injection - High Security Level
// Wider blacklist but "| " (pipe+space) is blacklisted, not "|" alone

if (isset($_POST['Submit'])) {
    $coupon = trim($_REQUEST['coupon']);
    $file   = dirname(__FILE__) . '/../coupons.txt';

    // Note: '| ' has a trailing space — so '|' without a space still works
    $substitutions = array(
        '&'  => '',
        ';'  => '',
        '| ' => '',
        '-'  => '',
        '$'  => '',
        '('  => '',
        ')'  => '',
        '`'  => '',
        '||' => '',
    );
    $coupon = str_replace(array_keys($substitutions), $substitutions, $coupon);

    // Still vulnerable: ROCKS2026|whoami (no space before pipe) bypasses the blacklist
    $cmd = shell_exec('cat ' . $file . ' | grep -i ' . $coupon);

    if ($cmd && trim($cmd) !== '') {
        $html .= '<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>Coupon accepted!</strong> Code <code>' . htmlspecialchars(trim($cmd)) . '</code> applied &mdash; 20% off your next ticket purchase.</div>';
    } else {
        $html .= '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Coupon not accepted.</strong> The code you entered is invalid or expired.</div>';
    }
}
