<?php
$base_path = '../../';
require_once '../../dvwa/includes/dvwaPage.inc.php';
$theme = getThemeConfig();
$page_title = 'SQL Injection - ' . $theme['vuln_sqli_title'];
require_once '../../dvwa/includes/header.php';

$html = '';
$security = dvwaSecurityLevelGet();

switch ($security) {
    case 'low':      require_once 'source/low.php'; break;
    case 'medium':   require_once 'source/medium.php'; break;
    case 'high':     require_once 'source/high.php'; break;
    case 'impossible': require_once 'source/impossible.php'; break;
}

// Auto-load current items from DB for hint list and medium dropdown
$bands = [];
$conn = dvwaDbConnect();
$bandResult = $conn->query("SELECT band_name, venue, city FROM concerts ORDER BY band_name ASC");
if ($bandResult) {
    while ($row = $bandResult->fetch_assoc()) {
        $bands[] = $row;
    }
}
$conn->close();
?>

<div class="page-header">
    <h1 class="page-title"><i class="<?php echo $theme['vuln_sqli_icon']; ?>"></i> <?php echo htmlspecialchars($theme['vuln_sqli_title']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['vuln_sqli_desc']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-search"></i> Search by <?php echo htmlspecialchars($theme['vuln_sqli_label']); ?>
            </div>
            <div class="card-body">
                <?php if ($security === 'high'): ?>
                    <p class="text-muted small mb-3">
                        <a href="#" onclick="window.open('session-input.php','MR_session','width=500,height=300');return false;" class="text-purple">
                            Click here to set the <?php echo strtolower($theme['vuln_sqli_label']); ?> (opens popup)
                        </a>
                    </p>
                <?php elseif ($security === 'medium'): ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select <?php echo htmlspecialchars($theme['item_label']); ?></label>
                            <select name="id" class="form-select">
                                <?php foreach ($bands as $b): ?>
                                <option value="<?php echo htmlspecialchars($b['band_name']); ?>">
                                    <?php echo htmlspecialchars($b['band_name']); ?> &mdash; <?php echo htmlspecialchars($b['venue']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="Submit" class="btn btn-purple">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </form>
                <?php else: ?>
                    <form method="GET">
                        <div class="mb-3">
                            <label class="form-label"><?php echo htmlspecialchars($theme['vuln_sqli_label']); ?></label>
                            <input type="text" name="id" class="form-control" placeholder="<?php echo htmlspecialchars($theme['vuln_sqli_placeholder']); ?>" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                        </div>
                        <button type="submit" name="Submit" class="btn btn-purple">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <?php if ($security === 'impossible'): ?>
                            <?php echo tokenField(); ?>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>

                <?php if ($html): ?>
                    <div class="vuln-output mt-3">
                        <span class="output-label">Results:</span>
                        <?php echo $html; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($bands)): ?>
        <div class="card mt-3">
            <div class="card-header"><i class="<?php echo $theme['card_icon']; ?>"></i> <?php echo htmlspecialchars($theme['item_label_plural']); ?> in Database (<?php echo count($bands); ?>)</div>
            <div class="card-body p-2">
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($bands as $b): ?>
                        <span class="genre-tag" style="cursor:default;"><?php echo htmlspecialchars($b['band_name']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Help Section -->
        <details class="help-panel">
            <summary><i class="fas fa-graduation-cap"></i> Learn About SQL Injection</summary>
            <div class="help-content">
                <p>SQL injection occurs when user input is included in a SQL query without proper sanitization or parameterization. Here the server queries the <code>concerts</code> table by <code>band_name</code>.</p>
                <h4>Low Level</h4>
                <p>User input is directly concatenated into the SQL query with no protection. Try: <code>' OR '1'='1</code> or <code>' UNION SELECT username, password FROM users-- -</code></p>
                <h4>Medium Level</h4>
                <p>Uses <code>htmlspecialchars()</code> which escapes HTML but does <strong>not</strong> escape single quotes &mdash; so SQL injection still works. The input comes from a dropdown, but you can modify the POST request. Try: <code>' OR '1'='1</code></p>
                <h4>High Level</h4>
                <p>Input comes from a session variable set via a popup window, and adds <code>LIMIT 1</code>. Still injectable via the popup. Try: <code>' OR '1'='1-- -</code></p>
                <h4>Impossible Level</h4>
                <p>Uses PDO prepared statements with bound parameters and an anti-CSRF token.</p>
            </div>
        </details>
    </div>
</div>

<?php require_once '../../dvwa/includes/footer.php'; ?>
