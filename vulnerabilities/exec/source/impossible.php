<?php
// Command Injection - Impossible Security Level
// No shell involved - validates against a PHP array whitelist

if (isset($_POST['Submit'])) {
    if (!checkToken($_POST['user_token'] ?? '', $_SESSION['session_token'] ?? '')) {
        $html .= '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Invalid CSRF token.</div>';
    } else {
        $coupon = strtoupper(trim($_REQUEST['coupon']));

        // Secure: no shell command, just an in-memory whitelist lookup
        $valid_coupons = [
            // Music theme
            'ROCKS2026' => '20% off Red Rocks shows',
            'SUMMER25'  => '25% off summer events',
            'DENVER10'  => '10% off Denver venues',
            'VIP50OFF'  => '50% off VIP upgrades',
            'EARLYBIRD' => '15% early-bird discount',
            'BOULDER15' => '15% off Boulder events',
            'FOCO20'    => '20% off Fort Collins events',
            'MUSICLOVER' => '10% loyalty discount',
            'FIRSTSHOW' => '20% off your first purchase',
            'GROUPDEAL' => '25% off groups of 4+',
            // Sports theme
            'NUGGETS2026' => '20% off Nuggets tickets',
            'BRONCOS20'   => '20% off Broncos tickets',
            'AVSFAN'      => '15% off Avalanche tickets',
            'ROCKIES10'   => '10% off Rockies tickets',
            // Games theme
            'GAMER2026'   => '20% off any game',
            'INDIE25'     => '25% off indie titles',
            'STEAM10'     => '10% store credit',
            'DLCFREE'     => 'Free DLC with purchase',
            // Cars theme
            'NOTACHANCE'  => 'Extended warranty (just kidding)',
            'GOODLUCK'    => '5% off (you\'ll need more than that)',
            'DUCTAPE'     => 'Free roll of duct tape',
            'PRAYERS'     => 'Our thoughts and prayers for your engine',
        ];

        if (isset($valid_coupons[$coupon])) {
            $desc = $valid_coupons[$coupon];
            $html .= '<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>Coupon accepted!</strong> <code>' . htmlspecialchars($coupon) . '</code> &mdash; ' . htmlspecialchars($desc) . '.</div>';
        } else {
            $html .= '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Coupon not accepted.</strong> The code you entered is invalid or expired.</div>';
        }
    }
    generateSessionToken();
}
