<?php
$base_path = '../../';
require_once '../../dvwa/includes/dvwaPage.inc.php';
$theme = getThemeConfig();
$page_title = 'XSS (Reflected) - ' . $theme['vuln_xss_r_title'];
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
    <h1 class="page-title"><i class="fas fa-code"></i> <?php echo htmlspecialchars($theme['vuln_xss_r_title']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['vuln_xss_r_desc']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-search"></i> Search</div>
            <div class="card-body">
                <form method="GET">
                    <div class="mb-3">
                        <label class="form-label">What are you looking for?</label>
                        <input type="text" name="name" class="form-control" placeholder="<?php echo htmlspecialchars($theme['vuln_xss_r_placeholder']); ?>" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-purple"><i class="fas fa-search"></i> Search</button>
                </form>

                <?php if ($html): ?>
                    <div class="mt-3"><?php echo $html; ?></div>
                <?php endif; ?>
            </div>
        </div>

        <details class="help-panel">
            <summary><i class="fas fa-graduation-cap"></i> Learn About Reflected XSS</summary>
            <div class="help-content">
                <p>Reflected XSS occurs when user input is immediately returned by the web application without proper encoding.</p>
                <h4>Low Level</h4>
                <p>No filtering at all. Try: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></p>
                <h4>Medium Level</h4>
                <p>Removes <code>&lt;script&gt;</code> tags. Try mixed case: <code>&lt;ScRiPt&gt;alert('XSS')&lt;/ScRiPt&gt;</code> or <code>&lt;img src=x onerror=alert('XSS')&gt;</code></p>
                <h4>High Level</h4>
                <p>Uses regex to strip script tags. Try other event handlers: <code>&lt;img src=x onerror=alert('XSS')&gt;</code></p>
                <h4>Impossible Level</h4>
                <p>Uses <code>htmlspecialchars()</code> to encode all HTML entities.</p>
            </div>
        </details>
    </div>
</div>

<?php require_once '../../dvwa/includes/footer.php'; ?>
