<?php
require_once 'dvwa/includes/dvwaPage.inc.php';

$error = '';

// Handle theme switch from login page
if (isset($_POST['theme']) && isset($GLOBALS['THEMES'][$_POST['theme']])) {
    $newTheme = $_POST['theme'];
    $oldTheme = getCurrentTheme();
    setTheme($newTheme);
    if ($newTheme !== $oldTheme) {
        dvwaReseedForTheme($newTheme);
    }
    dvwaRedirect('login.php');
}

if (isset($_POST['Login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $conn = dvwaDbConnect();
    $pass_hash = md5($password);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param('ss', $username, $pass_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];

        // Set default security level
        if (!isset($_COOKIE['security'])) {
            dvwaSecurityLevelSet('low');
        }

        dvwaRedirect('index.php');
    } else {
        $error = 'Invalid username or password.';
    }
    $conn->close();
}

if (isset($_GET['logout'])) {
    session_destroy();
    dvwaRedirect('login.php');
}

$theme = getThemeConfig();
$currentThemeKey = getCurrentTheme();
global $THEMES;
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($theme['name']); ?> - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --purple: <?php echo $theme['accent']; ?>;
            --purple-dark: <?php echo $theme['accent_dark']; ?>;
            --purple-light: <?php echo $theme['accent_light']; ?>;
            --purple-glow: <?php echo $theme['accent_glow']; ?>;
            --neon-pink: <?php echo $theme['secondary']; ?>;
        }
        .theme-pills { display: flex; gap: 0.4rem; justify-content: center; flex-wrap: wrap; margin-top: 0.75rem; }
        .theme-pill {
            border: none; background: #1a1a2e; color: #8892a4; padding: 0.3rem 0.75rem;
            font-size: 0.72rem; font-weight: 600; cursor: pointer;
            border-radius: 2px; transition: all 0.15s; display: inline-flex; align-items: center; gap: 0.35rem;
        }
        .theme-pill:hover { color: #e2e8f0; background: #252538; }
        .theme-pill.active { color: #fff; border-bottom: 2px solid var(--purple); }
        .theme-pill-music { --pill-color: #8b5cf6; }
        .theme-pill-sports { --pill-color: #f97316; }
        .theme-pill-games { --pill-color: #22c55e; }
        .theme-pill-cars { --pill-color: #ef4444; }
        .theme-pill.active.theme-pill-music { background: rgba(139,92,246,0.15); color: #a78bfa; border-color: #8b5cf6; }
        .theme-pill.active.theme-pill-sports { background: rgba(249,115,22,0.15); color: #fb923c; border-color: #f97316; }
        .theme-pill.active.theme-pill-games { background: rgba(34,197,94,0.15); color: #4ade80; border-color: #22c55e; }
        .theme-pill.active.theme-pill-cars { background: rgba(239,68,68,0.15); color: #f87171; border-color: #ef4444; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-brand">
                <h1><i class="<?php echo $theme['icon']; ?>"></i> <?php echo htmlspecialchars($theme['name']); ?></h1>
                <p><?php echo $theme['tagline']; ?></p>
                <p style="font-size:0.75rem;color:<?php echo $theme['secondary']; ?>;font-style:italic;opacity:0.7;margin-top:0.3rem;"><?php echo $theme['login_quip']; ?></p>
            </div>

            <!-- Theme selector pills -->
            <div class="theme-pills">
                <?php foreach ($THEMES as $tKey => $tCfg): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="theme" value="<?php echo $tKey; ?>">
                    <button type="submit" class="theme-pill theme-pill-<?php echo $tKey; ?> <?php echo $tKey === $currentThemeKey ? 'active' : ''; ?>">
                        <i class="<?php echo $tCfg['icon']; ?>"></i> <?php echo htmlspecialchars($tCfg['name']); ?>
                    </button>
                </form>
                <?php endforeach; ?>
            </div>

            <hr style="border-color:#252538; margin: 1rem 0;">

            <?php if ($error): ?>
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                    </div>
                </div>
                <button type="submit" name="Login" class="btn btn-purple w-100 py-2">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">
                    Default: <code>admin / password</code>
                </small>
            </div>
        </div>
    </div>
</body>
</html>
