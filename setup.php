<?php
require_once 'dvwa/includes/dvwaPage.inc.php';
require_once __DIR__ . '/setup_seeds.php';

$message = '';
$theme = getThemeConfig();
$themeKey = getCurrentTheme() ?? 'music';

// Auto-seed after theme switch
if (isset($_GET['auto_seed'])) {
    $_POST['create_db'] = true;
}

if (isset($_POST['create_db'])) {
    global $_DVWA;

    $conn = new mysqli(
        $_DVWA['db_server'],
        $_DVWA['db_user'],
        $_DVWA['db_password'],
        '',
        $_DVWA['db_port']
    );

    if ($conn->connect_error) {
        $message = '<div class="alert alert-danger">Connection failed: ' . $conn->connect_error . '</div>';
    } else {
        // Create database
        $conn->query("CREATE DATABASE IF NOT EXISTS `{$_DVWA['db_database']}`");
        $conn->select_db($_DVWA['db_database']);

        // Users table
        $conn->query("DROP TABLE IF EXISTS users");
        $conn->query("CREATE TABLE users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            avatar VARCHAR(255) DEFAULT 'default.png',
            last_login DATETIME,
            failed_login INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Default users (passwords are MD5 hashed like DVWA)
        $users = [
            ['admin', 'Admin', 'User', md5('password')],
            ['gordonb', 'Gordon', 'Brown', md5('abc123')],
            ['1337', 'Hack', 'Me', md5('charley')],
            ['pablo', 'Pablo', 'Picasso', md5('letmein')],
            ['smithy', 'Bob', 'Smith', md5('password')],
        ];

        $stmt = $conn->prepare("INSERT INTO users (username, first_name, last_name, password) VALUES (?, ?, ?, ?)");
        foreach ($users as $u) {
            $stmt->bind_param('ssss', $u[0], $u[1], $u[2], $u[3]);
            $stmt->execute();
        }

        // Concerts table (used by ALL themes - columns are reused with different semantics)
        $conn->query("DROP TABLE IF EXISTS concerts");
        $conn->query("CREATE TABLE concerts (
            concert_id INT AUTO_INCREMENT PRIMARY KEY,
            band_name VARCHAR(100) NOT NULL,
            venue VARCHAR(200) NOT NULL,
            city VARCHAR(100) NOT NULL,
            concert_date DATE NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            tickets_available INT NOT NULL,
            genre VARCHAR(50),
            poster_url VARCHAR(500),
            description TEXT,
            spotify_url VARCHAR(500) DEFAULT '',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Seed data based on theme
        $seedData = getSeedData($themeKey);
        $stmt = $conn->prepare("INSERT INTO concerts (band_name, venue, city, concert_date, price, tickets_available, genre, poster_url, description, spotify_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($seedData as $c) {
            $stmt->bind_param('ssssdissss', $c[0], $c[1], $c[2], $c[3], $c[4], $c[5], $c[6], $c[7], $c[8], $c[9]);
            $stmt->execute();
        }

        // Guestbook / Reviews table (for stored XSS)
        $conn->query("DROP TABLE IF EXISTS guestbook");
        $conn->query("CREATE TABLE guestbook (
            comment_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            comment TEXT NOT NULL,
            concert_id INT DEFAULT NULL,
            rating INT DEFAULT 5,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Orders table
        $conn->query("DROP TABLE IF EXISTS orders");
        $conn->query("CREATE TABLE orders (
            order_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            concert_id INT,
            quantity INT DEFAULT 1,
            total_price DECIMAL(10,2),
            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $message = '<div class="alert alert-success">Database created and populated with ' . htmlspecialchars($theme['name']) . ' data! <a href="login.php" class="alert-link">Login now</a></div>';
    }
    $conn->close();
}

if (isset($_POST['reset_db'])) {
    $_POST['create_db'] = true;
    include __FILE__;
    exit;
}

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($theme['name']); ?> Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
<body class="bg-dark">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark border-purple">
                    <div class="card-body text-center p-5">
                        <h1 class="display-4 text-purple mb-2"><i class="<?php echo $theme['icon']; ?>"></i> <?php echo htmlspecialchars($theme['name']); ?></h1>
                        <p class="text-muted mb-4"><?php echo htmlspecialchars($theme['tagline']); ?></p>
                        <hr class="border-purple">

                        <?php echo $message; ?>

                        <div class="my-4">
                            <h4 class="text-light">System Check</h4>
                            <table class="table table-dark table-bordered mt-3">
                                <tr>
                                    <td>PHP Version</td>
                                    <td><?php echo phpversion(); ?></td>
                                    <td><?php echo version_compare(phpversion(), '7.0', '>=') ? '<span class="text-success">OK</span>' : '<span class="text-danger">FAIL</span>'; ?></td>
                                </tr>
                                <tr>
                                    <td>MySQL Extension</td>
                                    <td><?php echo extension_loaded('mysqli') ? 'Installed' : 'Missing'; ?></td>
                                    <td><?php echo extension_loaded('mysqli') ? '<span class="text-success">OK</span>' : '<span class="text-danger">FAIL</span>'; ?></td>
                                </tr>
                                <tr>
                                    <td>allow_url_include</td>
                                    <td><?php echo ini_get('allow_url_include') ? 'On' : 'Off'; ?></td>
                                    <td><?php echo ini_get('allow_url_include') ? '<span class="text-success">OK</span>' : '<span class="text-warning">OFF</span>'; ?></td>
                                </tr>
                                <tr>
                                    <td>Uploads Directory</td>
                                    <td>hackable/uploads/</td>
                                    <td><?php echo is_writable('hackable/uploads/') ? '<span class="text-success">Writable</span>' : '<span class="text-warning">Not Writable</span>'; ?></td>
                                </tr>
                                <tr>
                                    <td>Active Theme</td>
                                    <td><?php echo htmlspecialchars($theme['name']); ?></td>
                                    <td><span class="text-success">OK</span></td>
                                </tr>
                            </table>
                        </div>

                        <form method="POST" class="d-flex gap-3 justify-content-center">
                            <button type="submit" name="create_db" class="btn btn-purple btn-lg">
                                Create / Reset Database
                            </button>
                        </form>

                        <div class="mt-4">
                            <p class="text-muted small">Default credentials: <code>admin / password</code></p>
                            <p class="text-muted small"><a href="theme_chooser.php?switch=1" class="text-purple">Switch Theme</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
