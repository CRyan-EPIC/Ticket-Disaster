<?php
$base_path = '../../';
require_once '../../dvwa/includes/dvwaPage.inc.php';
$theme = getThemeConfig();
$page_title = 'Brute Force - ' . $theme['vuln_brute_title'];
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
    <h1 class="page-title"><i class="fas fa-key"></i> <?php echo htmlspecialchars($theme['vuln_brute_title']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['vuln_brute_desc']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-lock"></i> Authentication</div>
            <div class="card-body">
                <form method="GET">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <button type="submit" name="Login" class="btn btn-purple"><i class="fas fa-sign-in-alt"></i> Access</button>
                    <?php if ($security === 'impossible') echo tokenField(); ?>
                </form>

                <?php if ($html): ?>
                    <div class="mt-3"><?php echo $html; ?></div>
                <?php endif; ?>
            </div>
        </div>

        <details class="help-panel">
            <summary><i class="fas fa-graduation-cap"></i> Learn About Brute Force</summary>
            <div class="help-content">
                <p>Brute force attacks try many username/password combinations to find valid credentials. Without rate limiting, they can test thousands per second.</p>
                <h4>Low Level</h4>
                <p>No rate limiting, no lockout, no CAPTCHA. Use <strong>Burp Suite Intruder</strong> or <strong>Hydra</strong> to automate password guessing.</p>
                <h4>Medium Level</h4>
                <p>Adds a 2-second sleep on failed login. Slows brute force but doesn't prevent it.</p>
                <h4>High Level</h4>
                <p>Random sleep (0-3 seconds) on failed login plus an anti-CSRF token that must be extracted from the page for each attempt.</p>
                <h4>Impossible Level</h4>
                <p>Account lockout after 3 failed attempts (15-minute lock), CSRF token, and prepared statements.</p>
            </div>
        </details>
    </div>
</div>

<?php require_once '../../dvwa/includes/footer.php'; ?>
