<?php
require_once dirname(__FILE__) . '/dvwaPage.inc.php';

if (!dvwaIsLoggedIn()) {
    dvwaRedirect('login.php');
}

// Redirect to theme chooser if no theme selected
if (getCurrentTheme() === null) {
    $bp = $base_path ?? '';
    dvwaRedirect($bp . 'theme_chooser.php');
}

// Send headers before ANY output
@header("X-XSS-Protection: 0");

$current_security = dvwaSecurityLevelGet();
$current_page = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$current_dir = basename(dirname($_SERVER['SCRIPT_FILENAME']));
$theme = getThemeConfig();

$active_module = '';
if (strpos($_SERVER['REQUEST_URI'], '/vulnerabilities/') !== false) {
    $parts = explode('/', $_SERVER['REQUEST_URI']);
    $idx = array_search('vulnerabilities', $parts);
    if ($idx !== false && isset($parts[$idx + 1])) {
        $active_module = $parts[$idx + 1];
    }
}
$bp = $base_path ?? '';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($theme['name']); ?> - <?php echo $page_title ?? 'Home'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo $bp; ?>assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --purple: <?php echo $theme['accent']; ?>;
            --purple-dark: <?php echo $theme['accent_dark']; ?>;
            --purple-light: <?php echo $theme['accent_light']; ?>;
            --purple-glow: <?php echo $theme['accent_glow']; ?>;
            --neon-pink: <?php echo $theme['secondary']; ?>;
        }
    </style>
    <script>
    // Apply sidebar/settings state before first paint to prevent flash
    (function() {
        var mobile = window.matchMedia('(max-width:768px)').matches;
        if (!mobile && localStorage.getItem('ticketdisaster_sidebar') === 'retracted') {
            document.documentElement.classList.add('td-sb-retracted');
        }
        if (localStorage.getItem('ticketdisaster_settings') === 'collapsed') {
            document.documentElement.classList.add('td-sb-settings-collapsed');
        }
    })();
    </script>
</head>
<body>
    <div class="app-layout" id="app">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo $bp; ?>index.php" class="sidebar-brand">
                    <span class="brand-icon"><i class="<?php echo $theme['icon']; ?>"></i></span>
                    <span class="nav-text"><?php echo htmlspecialchars($theme['name']); ?></span>
                </a>
                <div class="sidebar-subtitle nav-text"><?php echo htmlspecialchars($theme['tagline']); ?></div>
                <div class="sidebar-motto nav-text"><?php echo $theme['motto']; ?></div>
            </div>

            <div class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title"><?php echo htmlspecialchars($theme['nav_section']); ?></div>
                </div>
                <a href="<?php echo $bp; ?>index.php" class="nav-link <?php echo ($current_page === 'index' && $active_module === '') ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($theme['nav_dashboard']); ?>">
                    <span class="nav-icon"><i class="fas fa-home"></i></span><span class="nav-text"> <?php echo htmlspecialchars($theme['nav_dashboard']); ?></span>
                </a>

                <div class="nav-section">
                    <div class="nav-section-title">Attack Modules</div>
                </div>
                <a href="<?php echo $bp; ?>vulnerabilities/sqli/" class="nav-link <?php echo $active_module === 'sqli' ? 'active' : ''; ?>" title="SQL Injection">
                    <span class="nav-icon"><i class="fas fa-database"></i></span><span class="nav-text"> SQL Injection</span>
                </a>
                <a href="<?php echo $bp; ?>vulnerabilities/xss_r/" class="nav-link <?php echo $active_module === 'xss_r' ? 'active' : ''; ?>" title="XSS (Reflected)">
                    <span class="nav-icon"><i class="fas fa-code"></i></span><span class="nav-text"> XSS (Reflected)</span>
                </a>
                <a href="<?php echo $bp; ?>vulnerabilities/xss_s/" class="nav-link <?php echo $active_module === 'xss_s' ? 'active' : ''; ?>" title="XSS (Stored)">
                    <span class="nav-icon"><i class="fas fa-comment-dots"></i></span><span class="nav-text"> XSS (Stored)</span>
                </a>
                <a href="<?php echo $bp; ?>vulnerabilities/exec/" class="nav-link <?php echo $active_module === 'exec' ? 'active' : ''; ?>" title="Command Injection">
                    <span class="nav-icon"><i class="fas fa-tag"></i></span><span class="nav-text"> Command Injection</span>
                </a>
                <a href="<?php echo $bp; ?>vulnerabilities/upload/" class="nav-link <?php echo $active_module === 'upload' ? 'active' : ''; ?>" title="File Upload">
                    <span class="nav-icon"><i class="fas fa-upload"></i></span><span class="nav-text"> File Upload</span>
                </a>
                <a href="<?php echo $bp; ?>vulnerabilities/csrf/" class="nav-link <?php echo $active_module === 'csrf' ? 'active' : ''; ?>" title="CSRF">
                    <span class="nav-icon"><i class="fas fa-shield-alt"></i></span><span class="nav-text"> CSRF</span>
                </a>
                <a href="<?php echo $bp; ?>vulnerabilities/fi/" class="nav-link <?php echo $active_module === 'fi' ? 'active' : ''; ?>" title="File Inclusion">
                    <span class="nav-icon"><i class="fas fa-file-alt"></i></span><span class="nav-text"> File Inclusion</span>
                </a>
                <a href="<?php echo $bp; ?>vulnerabilities/brute/" class="nav-link <?php echo $active_module === 'brute' ? 'active' : ''; ?>" title="Brute Force">
                    <span class="nav-icon"><i class="fas fa-key"></i></span><span class="nav-text"> Brute Force</span>
                </a>

                <div class="nav-section nav-section-collapsible" onclick="toggleSettings()" id="settingsToggle">
                    <span class="nav-section-title">Settings</span>
                    <i class="fas fa-chevron-down settings-chevron" id="settingsChevron"></i>
                </div>
                <div id="settingsLinks">
                    <a href="<?php echo $bp; ?>security.php" class="nav-link <?php echo $current_page === 'security' ? 'active' : ''; ?>" title="Security Level">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span><span class="nav-text"> Security Level</span>
                    </a>
                    <a href="<?php echo $bp; ?>scraper.php" class="nav-link <?php echo $current_page === 'scraper' ? 'active' : ''; ?>" title="Scrape Data">
                        <span class="nav-icon"><i class="fas fa-spider"></i></span><span class="nav-text"> Scrape Data</span>
                    </a>
                    <a href="<?php echo $bp; ?>setup.php" class="nav-link <?php echo $current_page === 'setup' ? 'active' : ''; ?>" title="Setup / Reset DB">
                        <span class="nav-icon"><i class="fas fa-wrench"></i></span><span class="nav-text"> Setup / Reset DB</span>
                    </a>
                    <a href="<?php echo $bp; ?>theme_chooser.php?switch=1" class="nav-link" title="Switch Theme">
                        <span class="nav-icon"><i class="fas fa-palette"></i></span><span class="nav-text"> Switch Theme</span>
                    </a>
                    <a href="<?php echo $bp; ?>about.php" class="nav-link <?php echo $current_page === 'about' ? 'active' : ''; ?>" title="About">
                        <span class="nav-icon"><i class="fas fa-info-circle"></i></span><span class="nav-text"> About</span>
                    </a>
                    <a href="<?php echo $bp; ?>login.php?logout=1" class="nav-link" title="Logout">
                        <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span><span class="nav-text"> Logout</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="security-badge security-<?php echo $current_security; ?>">
                        <i class="fas fa-shield-alt"></i> <?php echo ucfirst($current_security); ?>
                    </span>
                </div>
                <div class="topbar-right">
                    <form method="POST" action="<?php echo $bp; ?>theme_switcher.php" class="d-inline-block me-3" id="themeQuickSwitch">
                        <select name="theme" class="form-select form-select-sm" style="width:auto;display:inline-block;background:#1a1a2e;color:#e2e8f0;border-color:var(--purple);font-size:0.78rem;padding:0.2rem 2rem 0.2rem 0.5rem;" onchange="this.form.submit()">
                            <?php
                            global $THEMES;
                            $curTheme = getCurrentTheme() ?? 'music';
                            foreach ($THEMES as $tKey => $tCfg): ?>
                                <option value="<?php echo $tKey; ?>" <?php echo $tKey === $curTheme ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tCfg['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <div class="user-menu">
                        <div class="user-avatar"><?php echo strtoupper(substr(dvwaCurrentUser(), 0, 1)); ?></div>
                        <span><?php echo dvwaCurrentUser(); ?></span>
                    </div>
                </div>
            </div>

            <div class="content-area">
