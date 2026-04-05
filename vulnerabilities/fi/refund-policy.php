<?php
// Theme-aware refund policy
if (!function_exists('getThemeConfig')) {
    require_once dirname(__FILE__) . '/../../config/config.inc.php';
    require_once dirname(__FILE__) . '/../../config/themes.php';
}
$_fi_theme = getThemeConfig();
$_fi_name = htmlspecialchars($_fi_theme['name']);
$_fi_accent = $_fi_theme['accent_light'];
$_fi_items = strtolower($_fi_theme['item_label_plural']);
?>
<h4 style="color:<?php echo $_fi_accent; ?>; margin-bottom:0.75rem;"><i class="fas fa-undo"></i> Refund Policy</h4>
<p><strong>Last updated:</strong> March 2026</p>

<p>At <?php echo $_fi_name; ?>, we want you to have the best experience possible. However, all <?php echo $_fi_items; ?> sales are final. Please review the following policies carefully before purchasing:</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">Cancelled <?php echo htmlspecialchars($_fi_theme['item_label_plural']); ?></h5>
<p>If a <?php echo strtolower(htmlspecialchars($_fi_theme['item_label'])); ?> is cancelled, you will receive a full refund to your original payment method within 7-10 business days. No action is required on your part.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">Postponed <?php echo htmlspecialchars($_fi_theme['item_label_plural']); ?></h5>
<p>If a <?php echo strtolower(htmlspecialchars($_fi_theme['item_label'])); ?> is rescheduled, your purchase will be valid for the new date. If you cannot make the new date, you may request a refund within 30 days of the announcement.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">No Refunds</h5>
<p>We do not offer refunds for: change of mind, inability to attend, or general dissatisfaction. Service fees are non-refundable under any circumstances.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">Contact Us</h5>
<p>For refund inquiries, email <code>refunds@<?php echo strtolower(str_replace(' ', '', $_fi_name)); ?>.fake</code> with your order number and reason for the request.</p>
