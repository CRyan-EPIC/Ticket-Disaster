<?php
if (!function_exists('getThemeConfig')) {
    require_once dirname(__FILE__) . '/../../config/config.inc.php';
    require_once dirname(__FILE__) . '/../../config/themes.php';
}
$_fi_theme = getThemeConfig();
$_fi_name = htmlspecialchars($_fi_theme['name']);
$_fi_accent = $_fi_theme['accent_light'];
$_fi_items = strtolower($_fi_theme['item_label_plural']);
$_fi_item = strtolower(htmlspecialchars($_fi_theme['item_label']));
?>
<h4 style="color:<?php echo $_fi_accent; ?>; margin-bottom:0.75rem;"><i class="fas fa-question-circle"></i> Frequently Asked Questions</h4>

<h5 style="color:#e2e8f0; margin-top:1rem;">How do I receive my purchase?</h5>
<p>All purchases are delivered electronically. After checkout, you'll receive a confirmation email with details. You can also access your orders in your <?php echo $_fi_name; ?> account under "My Orders."</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">Can I transfer my <?php echo $_fi_item; ?> to someone else?</h5>
<p>Yes! You can transfer purchases to another <?php echo $_fi_name; ?> user from your account dashboard. The recipient must have a <?php echo $_fi_name; ?> account. Transfers are free for the first two per order.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">What happens if something is sold out?</h5>
<p>You can join our waitlist for sold-out <?php echo $_fi_items; ?>. If availability opens up due to cancellations, waitlisted customers are notified in order. We do not guarantee availability.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">Do you offer VIP or premium packages?</h5>
<p>Some listings offer premium upgrades including early access, dedicated support, and exclusive perks. Check the individual listing page for availability.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">What's the deal with service fees?</h5>
<p>Service fees cover platform operation, payment processing, and customer support. We keep them as low as possible, but let's be honest &mdash; someone has to pay for the servers. Service fees are displayed before checkout and are non-refundable.</p>

<h5 style="color:#e2e8f0; margin-top:1rem;">Is there an age limit?</h5>
<p>Age restrictions vary by listing. Check the specific page for details. Most offerings are available to all ages, but some may have restrictions.</p>
