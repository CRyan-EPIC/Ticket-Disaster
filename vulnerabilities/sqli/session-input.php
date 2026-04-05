<?php
session_start();
require_once '../../config/config.inc.php';
require_once '../../config/themes.php';
$theme = getThemeConfig();

if (isset($_POST['id'])) {
    $_SESSION['id'] = $_POST['id'];
    echo "<p style='color: #22c55e;'>Value set.</p>";
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Set <?php echo htmlspecialchars($theme['vuln_sqli_label']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --purple: <?php echo $theme['accent']; ?>;
            --purple-dark: <?php echo $theme['accent_dark']; ?>;
            --purple-light: <?php echo $theme['accent_light']; ?>;
        }
    </style>
</head>
<body class="bg-dark p-4">
    <h5 class="text-purple mb-3">Set <?php echo htmlspecialchars($theme['vuln_sqli_label']); ?></h5>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="id" class="form-control" placeholder="<?php echo htmlspecialchars($theme['vuln_sqli_placeholder']); ?>">
        </div>
        <button type="submit" class="btn btn-purple btn-sm">Set</button>
    </form>
</body>
</html>
