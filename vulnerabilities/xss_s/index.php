<?php
$base_path = '../../';
require_once '../../dvwa/includes/dvwaPage.inc.php';
$theme = getThemeConfig();
$page_title = 'XSS (Stored) - ' . $theme['vuln_xss_s_title'];
require_once '../../dvwa/includes/header.php';

$html = '';
$security = dvwaSecurityLevelGet();

switch ($security) {
    case 'low':      require_once 'source/low.php'; break;
    case 'medium':   require_once 'source/medium.php'; break;
    case 'high':     require_once 'source/high.php'; break;
    case 'impossible': require_once 'source/impossible.php'; break;
}

// Fetch existing reviews
$conn = dvwaDbConnect();
$result = $conn->query("SELECT * FROM guestbook ORDER BY created_at DESC");
$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
$conn->close();
?>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-comment-dots"></i> <?php echo htmlspecialchars($theme['vuln_xss_s_title']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['vuln_xss_s_desc']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-pen"></i> Write a Review</div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="txtName" class="form-control" placeholder="<?php echo htmlspecialchars($theme['vuln_xss_s_name_placeholder']); ?>" maxlength="<?php echo $security === 'impossible' ? '10' : '100'; ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Your Review</label>
                            <textarea name="mtxMessage" class="form-control" rows="3" placeholder="<?php echo htmlspecialchars($theme['vuln_xss_s_msg_placeholder']); ?>"></textarea>
                        </div>
                    </div>
                    <?php if ($security === 'impossible') echo tokenField(); ?>
                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" name="btnSign" class="btn btn-purple"><i class="fas fa-paper-plane"></i> Post Review</button>
                        <a href="?clear=1" class="btn btn-outline-purple"><i class="fas fa-trash"></i> Clear Reviews</a>
                    </div>
                </form>
                <?php if ($html): ?>
                    <div class="alert alert-info mt-3"><?php echo $html; ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Existing reviews -->
        <div class="card">
            <div class="card-header"><i class="fas fa-comments"></i> Community Reviews (<?php echo count($reviews); ?>)</div>
            <div class="card-body">
                <?php if (empty($reviews)): ?>
                    <p class="text-muted">No reviews yet. Be the first to share your experience!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <div class="review-header">
                                <span class="review-author"><?php echo $review['name']; ?></span>
                                <span class="review-date"><?php echo $review['created_at']; ?></span>
                            </div>
                            <div class="review-body"><?php echo $review['comment']; ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_GET['clear'])): ?>
            <?php
            $conn = dvwaDbConnect();
            $conn->query("TRUNCATE TABLE guestbook");
            $conn->close();
            echo '<script>window.location="index.php";</script>';
            ?>
        <?php endif; ?>

        <details class="help-panel">
            <summary><i class="fas fa-graduation-cap"></i> Learn About Stored XSS</summary>
            <div class="help-content">
                <p>Stored XSS is the most dangerous form of XSS. The malicious script is permanently stored on the server and served to every user who views the page.</p>
                <h4>Low Level</h4>
                <p>No HTML encoding on output. Both name and message fields are vulnerable. Try: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code> in the message field.</p>
                <h4>Medium Level</h4>
                <p>Message field uses <code>htmlspecialchars()</code> and <code>strip_tags()</code>, but the name field only strips <code>&lt;script&gt;</code> tags. Bypass the name field's 10-char limit via dev tools.</p>
                <h4>High Level</h4>
                <p>Message is fully encoded. Name uses regex to strip script tags but is still vulnerable to other tags like <code>&lt;img onerror=...&gt;</code>.</p>
                <h4>Impossible Level</h4>
                <p>Both fields use <code>htmlspecialchars()</code> with anti-CSRF token.</p>
            </div>
        </details>
    </div>
</div>

<?php require_once '../../dvwa/includes/footer.php'; ?>
