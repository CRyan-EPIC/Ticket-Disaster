<?php
$base_path = '../../';
require_once '../../dvwa/includes/dvwaPage.inc.php';
$theme = getThemeConfig();
$page_title = 'Command Injection - ' . $theme['vuln_exec_title'];
require_once '../../dvwa/includes/header.php';

$html = '';
$security = dvwaSecurityLevelGet();

switch ($security) {
    case 'low':      require_once 'source/low.php'; break;
    case 'medium':   require_once 'source/medium.php'; break;
    case 'high':     require_once 'source/high.php'; break;
    case 'impossible': require_once 'source/impossible.php'; break;
}
?>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($theme['vuln_exec_title']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['vuln_exec_desc']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-percent"></i> Redeem Code</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($theme['vuln_exec_label']); ?></label>
                        <input type="text" name="coupon" class="form-control" placeholder="<?php echo htmlspecialchars($theme['vuln_exec_placeholder']); ?>" value="<?php echo isset($_POST['coupon']) ? htmlspecialchars($_POST['coupon']) : ''; ?>">
                        <small class="text-muted mt-1 d-block"><?php echo htmlspecialchars($theme['vuln_exec_hint']); ?></small>
                    </div>
                    <button type="submit" name="Submit" class="btn btn-purple"><i class="fas fa-check"></i> Apply</button>
                    <?php if ($security === 'impossible') echo tokenField(); ?>
                </form>

                <?php if ($html): ?>
                    <div class="mt-3"><?php echo $html; ?></div>
                <?php endif; ?>
            </div>
        </div>

        <details class="help-panel">
            <summary><i class="fas fa-graduation-cap"></i> Learn About Command Injection</summary>
            <div class="help-content">
                <p>Command injection occurs when user input is passed to a system shell without proper sanitization. Here the server uses <code>grep</code> to look up codes in a file.</p>
                <h4>Low Level</h4>
                <p>No sanitization. The code is passed directly to a shell command. Try: <code>ROCKS2026; whoami</code> or <code>ROCKS2026; cat /etc/passwd</code></p>
                <h4>Medium Level</h4>
                <p>Removes <code>&&</code> and <code>;</code>. Try: <code>ROCKS2026 | whoami</code> or <code>ROCKS2026 || whoami</code></p>
                <h4>High Level</h4>
                <p>Blacklists more shell operators but <code>|</code> without a trailing space still works. Try: <code>ROCKS2026|whoami</code></p>
                <h4>Impossible Level</h4>
                <p>Uses PHP's <code>in_array()</code> to check against a whitelist. No shell command is executed.</p>
            </div>
        </details>
    </div>
</div>

<?php require_once '../../dvwa/includes/footer.php'; ?>
