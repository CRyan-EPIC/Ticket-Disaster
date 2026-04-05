<?php
$base_path = '../../';
require_once '../../dvwa/includes/dvwaPage.inc.php';
$theme = getThemeConfig();
$page_title = 'CSRF - ' . $theme['vuln_csrf_title'];
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
    <h1 class="page-title"><i class="fas fa-shield-alt"></i> <?php echo htmlspecialchars($theme['vuln_csrf_title']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['vuln_csrf_desc']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-key"></i> Change Password</div>
            <div class="card-body">
                <form method="GET">
                    <?php if ($security === 'impossible'): ?>
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="password_current" class="form-control" placeholder="Enter current password">
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password_new" class="form-control" placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_conf" class="form-control" placeholder="Confirm new password">
                    </div>
                    <button type="submit" name="Change" class="btn btn-purple"><i class="fas fa-save"></i> Change Password</button>
                    <?php if ($security === 'high' || $security === 'impossible') echo tokenField(); ?>
                </form>

                <?php if ($html): ?>
                    <div class="alert mt-3 <?php echo strpos($html, 'changed') !== false ? 'alert-success' : 'alert-danger'; ?>">
                        <?php echo $html; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <details class="help-panel">
            <summary><i class="fas fa-graduation-cap"></i> Learn About CSRF</summary>
            <div class="help-content">
                <p>Cross-Site Request Forgery tricks a user's browser into making unwanted requests to a site where they're authenticated.</p>
                <h4>Low Level</h4>
                <p>No CSRF protection. Craft a URL like: <code>?password_new=hacked&password_conf=hacked&Change=Change</code> and trick a logged-in user into visiting it.</p>
                <h4>Medium Level</h4>
                <p>Checks the HTTP Referer header. Can be bypassed by hosting the attack page on the same domain or manipulating the Referer.</p>
                <h4>High Level</h4>
                <p>Uses anti-CSRF tokens. The token must be extracted from the page first (e.g., via XSS).</p>
                <h4>Impossible Level</h4>
                <p>Requires the current password, uses anti-CSRF token, and uses prepared statements.</p>
            </div>
        </details>
    </div>
</div>

<?php require_once '../../dvwa/includes/footer.php'; ?>
