<?php
$base_path = '../../';
require_once '../../dvwa/includes/dvwaPage.inc.php';
$theme = getThemeConfig();
$page_title = 'File Upload - ' . $theme['vuln_upload_title'];
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
    <h1 class="page-title"><i class="fas fa-upload"></i> <?php echo htmlspecialchars($theme['vuln_upload_title']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['vuln_upload_desc']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-image"></i> Upload Image</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Choose a file</label>
                        <input type="file" name="uploaded" class="form-control" accept="image/*">
                        <small class="text-muted mt-1 d-block">Supported: JPG, PNG, GIF (max 100KB for impossible level)</small>
                    </div>
                    <button type="submit" name="Upload" class="btn btn-purple"><i class="fas fa-cloud-upload-alt"></i> Upload</button>
                    <?php if ($security === 'impossible') echo tokenField(); ?>
                </form>

                <?php if ($html): ?>
                    <div class="alert mt-3 <?php echo strpos($html, 'success') !== false ? 'alert-success' : 'alert-danger'; ?>">
                        <?php echo $html; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <details class="help-panel">
            <summary><i class="fas fa-graduation-cap"></i> Learn About File Upload Vulnerabilities</summary>
            <div class="help-content">
                <p>Unrestricted file uploads allow attackers to upload malicious files (like PHP webshells) that can then be executed on the server.</p>
                <h4>Low Level</h4>
                <p>No validation at all. Upload a PHP file like <code>&lt;?php system($_GET['cmd']); ?&gt;</code> and access it at <code>/hackable/uploads/yourfile.php</code>.</p>
                <h4>Medium Level</h4>
                <p>Checks MIME type (Content-Type header) but this can be spoofed. Upload a PHP file but set Content-Type to <code>image/jpeg</code>.</p>
                <h4>High Level</h4>
                <p>Checks file extension. Only allows jpg/jpeg/png. Try a double extension like <code>shell.php.jpg</code> or use getimagesize bypass.</p>
                <h4>Impossible Level</h4>
                <p>Checks extension, MIME type, re-creates the image with GD library, renames the file, and limits size.</p>
            </div>
        </details>
    </div>
</div>

<?php require_once '../../dvwa/includes/footer.php'; ?>
