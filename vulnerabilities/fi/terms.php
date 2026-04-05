<?php
if (!function_exists('getThemeConfig')) {
    require_once dirname(__FILE__) . '/../../config/config.inc.php';
    require_once dirname(__FILE__) . '/../../config/themes.php';
}
$_fi_theme = getThemeConfig();
$_fi_name = htmlspecialchars($_fi_theme['name']);
$_fi_accent = $_fi_theme['accent_light'];
$_fi_items = strtolower($_fi_theme['item_label_plural']);
?>
<h4 style="color:<?php echo $_fi_accent; ?>; margin-bottom:0.75rem;"><i class="fas fa-gavel"></i> Terms of Service</h4>
<p><strong>Effective:</strong> January 1, 2026</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">1. Acceptance of Terms</h5>
<p>By accessing or using <?php echo $_fi_name; ?>, you agree to be bound by these Terms of Service. If you do not agree, please do not use our platform.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">2. Account Registration</h5>
<p>You must create an account to make purchases. You are responsible for maintaining the confidentiality of your login credentials. You agree to provide accurate information and to update it as necessary.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">3. Purchases</h5>
<p>All prices are in USD and include applicable taxes. <?php echo htmlspecialchars($_fi_theme['item_label_plural']); ?> are subject to availability. <?php echo $_fi_name; ?> reserves the right to limit quantities per customer. Resale of items purchased through <?php echo $_fi_name; ?> is prohibited without prior written consent.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">4. Prohibited Conduct</h5>
<p>You agree not to: use bots or automated tools to make purchases, create multiple accounts to circumvent limits, attempt to access restricted areas of the platform, or engage in any activity that disrupts the service.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">5. Limitation of Liability</h5>
<p><?php echo $_fi_name; ?> is not responsible for the quality of <?php echo $_fi_items; ?>, venue conditions, or any personal injury. Our liability is limited to the face value of purchases made.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">6. Governing Law</h5>
<p>These terms are governed by the laws of the State of Colorado. Any disputes shall be resolved in the courts of Denver County.</p>
