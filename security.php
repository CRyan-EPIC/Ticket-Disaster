<?php
$page_title = 'Security Level';
$base_path = '';
// Handle POST before any HTML output so setcookie() works
require_once 'dvwa/includes/dvwaPage.inc.php';
if (isset($_POST['seclev_submit'])) {
    dvwaSecurityLevelSet($_POST['security']);
}
$theme = getThemeConfig();
require_once 'dvwa/includes/header.php';
$current_security = dvwaSecurityLevelGet();
?>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-shield-alt"></i> Security Level</h1>
    <p class="page-subtitle">Control the difficulty of the vulnerabilities</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Set Security Level</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Current Level: <strong class="text-purple"><?php echo ucfirst($current_security); ?></strong></label>
                        <select name="security" class="form-select">
                            <option value="low" <?php echo $current_security === 'low' ? 'selected' : ''; ?>>Low</option>
                            <option value="medium" <?php echo $current_security === 'medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="high" <?php echo $current_security === 'high' ? 'selected' : ''; ?>>High</option>
                            <option value="impossible" <?php echo $current_security === 'impossible' ? 'selected' : ''; ?>>Impossible</option>
                        </select>
                    </div>
                    <button type="submit" name="seclev_submit" class="btn btn-purple">Update</button>
                </form>

                <hr class="my-4 border-secondary">

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                            <h6 class="text-danger mb-1">Low</h6>
                            <small class="text-muted">Zero security. All inputs go directly into dangerous operations. This is the "how NOT to do it" example.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);">
                            <h6 class="text-warning mb-1">Medium</h6>
                            <small class="text-muted">Partial protections with common mistakes. Bad filtering, incomplete escaping, or bypassable checks.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2);">
                            <h6 class="text-success mb-1">High</h6>
                            <small class="text-muted">Stronger defenses but still flawed. Requires creative exploitation to bypass.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.2);">
                            <h6 style="color: #06b6d4" class="mb-1">Impossible</h6>
                            <small class="text-muted">Properly secured. Prepared statements, CSRF tokens, strict validation. The secure reference implementation.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'dvwa/includes/footer.php'; ?>
