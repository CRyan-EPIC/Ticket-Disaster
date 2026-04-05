<?php
require_once 'dvwa/includes/dvwaPage.inc.php';

$error = '';

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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-brand">
                <h1><i class="<?php echo $theme['icon']; ?>"></i> <?php echo htmlspecialchars($theme['name']); ?></h1>
                <p><?php echo htmlspecialchars($theme['tagline']); ?></p>
                <p style="font-size:0.75rem;color:<?php echo $theme['secondary']; ?>;font-style:italic;opacity:0.7;margin-top:0.3rem;"><?php echo $theme['login_quip']; ?></p>
            </div>

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
                    Default: <code>admin / password</code><br>
                    <a href="setup.php" class="text-purple">Setup Database</a> |
                    <a href="theme_chooser.php?switch=1" class="text-purple">Switch Theme</a>
                </small>
            </div>
        </div>
    </div>
</body>
</html>
