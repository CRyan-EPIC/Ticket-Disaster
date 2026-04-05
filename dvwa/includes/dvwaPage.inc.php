<?php

session_start();

require_once dirname(__FILE__) . '/../../config/config.inc.php';
require_once dirname(__FILE__) . '/../../config/themes.php';

// Security levels
$security_levels = array('low', 'medium', 'high', 'impossible');

function dvwaSecurityLevelGet() {
    return isset($_COOKIE['security']) ? $_COOKIE['security'] : 'low';
}

function dvwaSecurityLevelSet($level) {
    global $security_levels;
    if (in_array($level, $security_levels)) {
        setcookie('security', $level, time() + 3600 * 24 * 30, '/');
        $_COOKIE['security'] = $level;
    }
}

function dvwaIsLoggedIn() {
    return isset($_SESSION['username']);
}

function dvwaRedirect($url) {
    header("Location: $url");
    exit;
}

function dvwaMessagePush($message) {
    $_SESSION['flash_message'] = $message;
}

function dvwaMessagePop() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return '';
}

/**
 * Connect to the database. Auto-creates the database, tables, and seed data
 * on first run so the user never needs to manually click "Setup".
 */
function dvwaDbConnect() {
    global $_DVWA;

    // First try connecting to the target database directly
    $conn = @new mysqli(
        $_DVWA['db_server'],
        $_DVWA['db_user'],
        $_DVWA['db_password'],
        $_DVWA['db_database'],
        $_DVWA['db_port']
    );

    if ($conn->connect_error) {
        // Database probably doesn't exist yet — bootstrap it
        $conn = @new mysqli(
            $_DVWA['db_server'],
            $_DVWA['db_user'],
            $_DVWA['db_password'],
            '',
            $_DVWA['db_port']
        );
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        // Create the database
        $conn->query("CREATE DATABASE IF NOT EXISTS `{$_DVWA['db_database']}`");
        $conn->select_db($_DVWA['db_database']);

        // Bootstrap tables + seed
        dvwaBootstrapDb($conn);

        return $conn;
    }

    // Check if the users table exists (i.e. DB was initialized)
    $check = $conn->query("SHOW TABLES LIKE 'users'");
    if ($check && $check->num_rows === 0) {
        dvwaBootstrapDb($conn);
    }

    return $conn;
}

/**
 * Create all tables and seed initial data.
 */
function dvwaBootstrapDb($conn) {
    // Users table
    $conn->query("CREATE TABLE IF NOT EXISTS users (
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

    // Seed users only if empty
    $check = $conn->query("SELECT COUNT(*) as c FROM users");
    $row = $check->fetch_assoc();
    if ((int)$row['c'] === 0) {
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
    }

    // Concerts table
    $conn->query("CREATE TABLE IF NOT EXISTS concerts (
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

    // Seed concerts only if empty
    $check = $conn->query("SELECT COUNT(*) as c FROM concerts");
    $row = $check->fetch_assoc();
    if ((int)$row['c'] === 0) {
        dvwaSeedConcerts($conn);
    }

    // Guestbook table
    $conn->query("CREATE TABLE IF NOT EXISTS guestbook (
        comment_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        comment TEXT NOT NULL,
        concert_id INT DEFAULT NULL,
        rating INT DEFAULT 5,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Orders table
    $conn->query("CREATE TABLE IF NOT EXISTS orders (
        order_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        concert_id INT,
        quantity INT DEFAULT 1,
        total_price DECIMAL(10,2),
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}

/**
 * Seed the concerts table with data for the current theme.
 */
function dvwaSeedConcerts($conn) {
    $themeKey = getCurrentTheme() ?? 'music';

    require_once dirname(__FILE__) . '/../../setup_seeds.php';
    $seedData = getSeedData($themeKey);
    $stmt = $conn->prepare("INSERT INTO concerts (band_name, venue, city, concert_date, price, tickets_available, genre, poster_url, description, spotify_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($seedData as $c) {
        $stmt->bind_param('ssssdissss', $c[0], $c[1], $c[2], $c[3], $c[4], $c[5], $c[6], $c[7], $c[8], $c[9]);
        $stmt->execute();
    }
}

/**
 * Re-seed ONLY the concerts table for a theme switch (preserves users + guestbook).
 */
function dvwaReseedForTheme($themeKey) {
    global $_DVWA;
    $conn = new mysqli(
        $_DVWA['db_server'],
        $_DVWA['db_user'],
        $_DVWA['db_password'],
        $_DVWA['db_database'],
        $_DVWA['db_port']
    );
    if ($conn->connect_error) return false;

    $conn->query("DELETE FROM concerts");

    require_once dirname(__FILE__) . '/../../setup_seeds.php';
    $seedData = getSeedData($themeKey);
    if (!empty($seedData)) {
        $stmt = $conn->prepare("INSERT INTO concerts (band_name, venue, city, concert_date, price, tickets_available, genre, poster_url, description, spotify_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($seedData as $c) {
            $stmt->bind_param('ssssdissss', $c[0], $c[1], $c[2], $c[3], $c[4], $c[5], $c[6], $c[7], $c[8], $c[9]);
            $stmt->execute();
        }
    }
    $conn->close();
    return true;
}

function dvwaCurrentUser() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
}

function generateSessionToken() {
    if (empty($_SESSION['session_token'])) {
        $_SESSION['session_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['session_token'];
}

function checkToken($token, $session_token) {
    return ($token === $session_token);
}

function dvwaHtmlEcho($html) {
    echo $html;
}

// CSRF token generation for impossible level
function tokenField() {
    $token = generateSessionToken();
    return '<input type="hidden" name="user_token" value="' . $token . '">';
}
