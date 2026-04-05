<?php
/**
 * Theme definitions for TicketDisaster.
 * Each theme re-skins the same underlying DB structure (concerts table).
 * Column mappings: band_name → item name, venue → location, etc.
 */

$THEMES = [

    /* ================================================================
       MUSIC — Concert tickets (the original theme)
       ================================================================ */
    'music' => [
        'name'        => 'TicketDisaster',
        'tagline'     => 'Vulnerable Concert Ticket Shop',
        'motto'       => '"We handle your passwords like we handle mosh pits &mdash; no protection."',
        'login_quip'  => '"Your data is our encore."',
        'icon'        => 'fas fa-ticket-alt',
        'accent'      => '#8b5cf6',        // purple
        'accent_dark' => '#6d28d9',
        'accent_light'=> '#a78bfa',
        'accent_glow' => 'rgba(139,92,246,0.25)',
        'secondary'   => '#ec4899',        // pink
        'css_class'   => 'theme-music',

        // Labels for the concerts table columns
        'item_label'      => 'Band',
        'item_label_plural'=> 'Concerts',
        'venue_label'     => 'Venue',
        'date_label'      => 'Date',
        'price_label'     => 'Price',
        'genre_label'     => 'Genre',
        'link_label'      => 'Spotify',
        'link_icon'       => 'fab fa-spotify',
        'link_color'      => '#1DB954',
        'card_icon'       => 'fas fa-music',
        'poster_icon'     => 'fas fa-music',

        // Dashboard
        'dash_title'      => 'Upcoming Shows',
        'dash_subtitle'   => 'Colorado Front Range &mdash; Red Rocks, Denver, Boulder, Fort Collins',
        'dash_icon'       => 'fas fa-fire',

        // Sidebar nav labels
        'nav_section'     => 'Shop',
        'nav_dashboard'   => 'Dashboard',

        // Vulnerability module theming
        'vuln_sqli_title'   => 'Concert Search',
        'vuln_sqli_desc'    => 'Look up concerts by band name',
        'vuln_sqli_label'   => 'Band Name',
        'vuln_sqli_placeholder' => 'Enter band name (e.g. Goose)',
        'vuln_sqli_icon'    => 'fas fa-database',

        'vuln_xss_r_title'  => 'Concert Search',
        'vuln_xss_r_desc'   => 'Search for concerts, artists, and venues',
        'vuln_xss_r_placeholder' => 'Search concerts, bands, venues...',

        'vuln_xss_s_title'  => 'Concert Reviews',
        'vuln_xss_s_desc'   => 'Share your concert experience with the community',
        'vuln_xss_s_name_placeholder' => 'Your name',
        'vuln_xss_s_msg_placeholder'  => 'Share your experience...',

        'vuln_exec_title'   => 'Apply Coupon Code',
        'vuln_exec_desc'    => 'Enter a promo code to get a discount on your ticket purchase',
        'vuln_exec_label'   => 'Coupon Code',
        'vuln_exec_placeholder' => 'e.g. ROCKS2026',
        'vuln_exec_hint'    => 'Try: ROCKS2026, SUMMER25, DENVER10, VIP50OFF',

        'vuln_upload_title' => 'Profile Picture Upload',
        'vuln_upload_desc'  => 'Upload your avatar or profile picture for your account',

        'vuln_csrf_title'   => 'Account Security',
        'vuln_csrf_desc'    => 'Change your account password',

        'vuln_fi_title'     => 'Policies & Help',
        'vuln_fi_desc'      => 'Read our terms, refund policies, and FAQs before purchasing',

        'vuln_brute_title'  => 'VIP Backstage Access',
        'vuln_brute_desc'   => 'Login to access exclusive backstage passes and VIP perks',
        'vuln_brute_success'=> 'Welcome to the VIP area',
        'vuln_brute_flavor' => 'You now have backstage access to all concerts.',

        // About page
        'about_what'        => 'TicketDisaster is a deliberately vulnerable web application themed as a modern concert ticket shop.',
        'about_modules'     => [
            ['Ticket Search',    'Search bookings by ID',       'SQL Injection'],
            ['Concert Search',   'Search for concerts',         'XSS (Reflected)'],
            ['Concert Reviews',  'Community guestbook',         'XSS (Stored)'],
            ['Venue Check',      'Ping venue servers',          'Command Injection'],
            ['Profile Picture',  'Upload avatar',               'File Upload'],
            ['Account Security', 'Change password',             'CSRF'],
            ['Venue Info',       'View venue pages',            'File Inclusion'],
            ['VIP Access',       'Backstage login',             'Brute Force'],
        ],
    ],

    /* ================================================================
       SPORTS — Denver area sports tickets
       ================================================================ */
    'sports' => [
        'name'        => 'FanCrash',
        'tagline'     => 'Vulnerable Sports Ticket Shop',
        'motto'       => '"Go Denver! Our teams are unstoppable &mdash; our security, not so much."',
        'login_quip'  => '"Mile High pride, mile-low security."',
        'icon'        => 'fas fa-football-ball',
        'accent'      => '#f97316',        // orange
        'accent_dark' => '#c2410c',
        'accent_light'=> '#fb923c',
        'accent_glow' => 'rgba(249,115,22,0.25)',
        'secondary'   => '#3b82f6',        // blue
        'css_class'   => 'theme-sports',

        'item_label'      => 'Matchup',
        'item_label_plural'=> 'Games',
        'venue_label'     => 'Arena',
        'date_label'      => 'Game Day',
        'price_label'     => 'Price',
        'genre_label'     => 'Sport',
        'link_label'      => 'ESPN',
        'link_icon'       => 'fas fa-tv',
        'link_color'      => '#d00',
        'card_icon'       => 'fas fa-trophy',
        'poster_icon'     => 'fas fa-trophy',

        'dash_title'      => 'Upcoming Games',
        'dash_subtitle'   => 'Denver Metro &mdash; Nuggets, Avalanche, Rockies, Broncos, CU Buffs &amp; more',
        'dash_icon'       => 'fas fa-fire',

        'nav_section'     => 'Tickets',
        'nav_dashboard'   => 'Dashboard',

        'vuln_sqli_title'   => 'Game Search',
        'vuln_sqli_desc'    => 'Look up games by team name',
        'vuln_sqli_label'   => 'Team Name',
        'vuln_sqli_placeholder' => 'Enter team name (e.g. Nuggets)',
        'vuln_sqli_icon'    => 'fas fa-database',

        'vuln_xss_r_title'  => 'Game Search',
        'vuln_xss_r_desc'   => 'Search for games, teams, and arenas',
        'vuln_xss_r_placeholder' => 'Search games, teams, arenas...',

        'vuln_xss_s_title'  => 'Fan Reviews',
        'vuln_xss_s_desc'   => 'Share your game day experience with other fans',
        'vuln_xss_s_name_placeholder' => 'Your name',
        'vuln_xss_s_msg_placeholder'  => 'How was the game?',

        'vuln_exec_title'   => 'Apply Promo Code',
        'vuln_exec_desc'    => 'Enter a promo code to get a discount on your ticket purchase',
        'vuln_exec_label'   => 'Promo Code',
        'vuln_exec_placeholder' => 'e.g. NUGGETS2026',
        'vuln_exec_hint'    => 'Try: NUGGETS2026, BRONCOS20, AVSFAN, ROCKIES10',

        'vuln_upload_title' => 'Fan Avatar Upload',
        'vuln_upload_desc'  => 'Upload your fan photo or team avatar',

        'vuln_csrf_title'   => 'Account Security',
        'vuln_csrf_desc'    => 'Change your FanCrash account password',

        'vuln_fi_title'     => 'Policies & Help',
        'vuln_fi_desc'      => 'Read our terms, refund policies, and FAQs before purchasing',

        'vuln_brute_title'  => 'Press Box Access',
        'vuln_brute_desc'   => 'Login to access exclusive press box passes and VIP perks',
        'vuln_brute_success'=> 'Welcome to the Press Box',
        'vuln_brute_flavor' => 'You now have courtside access to all games.',

        'about_what'        => 'FanCrash is a deliberately vulnerable web application themed as a Denver-area sports ticket shop.',
        'about_modules'     => [
            ['Game Search',      'Search games by team',        'SQL Injection'],
            ['Game Finder',      'Search for games',            'XSS (Reflected)'],
            ['Fan Reviews',      'Community board',             'XSS (Stored)'],
            ['Promo Checker',    'Validate promo codes',        'Command Injection'],
            ['Fan Avatar',       'Upload profile pic',          'File Upload'],
            ['Account Security', 'Change password',             'CSRF'],
            ['Stadium Info',     'View arena pages',            'File Inclusion'],
            ['Press Box Login',  'VIP access',                  'Brute Force'],
        ],
    ],

    /* ================================================================
       GAMES — Steam-like video game store
       ================================================================ */
    'games' => [
        'name'        => 'Flux',
        'tagline'     => 'Vulnerable Game Store',
        'motto'       => '"We ship bugs faster than any AAA studio."',
        'login_quip'  => '"Your account security? We left it in Early Access."',
        'icon'        => 'fas fa-gamepad',
        'accent'      => '#22c55e',        // green (Steam-ish)
        'accent_dark' => '#15803d',
        'accent_light'=> '#4ade80',
        'accent_glow' => 'rgba(34,197,94,0.25)',
        'secondary'   => '#3b82f6',        // blue
        'css_class'   => 'theme-games',

        'item_label'      => 'Game',
        'item_label_plural'=> 'Games',
        'venue_label'     => 'Platform',
        'date_label'      => 'Release Date',
        'price_label'     => 'Price',
        'genre_label'     => 'Genre',
        'link_label'      => 'Steam',
        'link_icon'       => 'fab fa-steam',
        'link_color'      => '#1b2838',
        'card_icon'       => 'fas fa-gamepad',
        'poster_icon'     => 'fas fa-gamepad',

        'dash_title'      => 'Hot & Upcoming',
        'dash_subtitle'   => 'Trending games, new releases, and pre-orders',
        'dash_icon'       => 'fas fa-fire',

        'nav_section'     => 'Store',
        'nav_dashboard'   => 'Store Home',

        'vuln_sqli_title'   => 'Game Search',
        'vuln_sqli_desc'    => 'Look up games by title',
        'vuln_sqli_label'   => 'Game Title',
        'vuln_sqli_placeholder' => 'Enter game title (e.g. Elden Ring)',
        'vuln_sqli_icon'    => 'fas fa-database',

        'vuln_xss_r_title'  => 'Game Search',
        'vuln_xss_r_desc'   => 'Search for games, developers, and genres',
        'vuln_xss_r_placeholder' => 'Search games, studios, genres...',

        'vuln_xss_s_title'  => 'Game Reviews',
        'vuln_xss_s_desc'   => 'Rate and review games you have played',
        'vuln_xss_s_name_placeholder' => 'Your gamer tag',
        'vuln_xss_s_msg_placeholder'  => 'Write your review...',

        'vuln_exec_title'   => 'Redeem Game Key',
        'vuln_exec_desc'    => 'Enter a product key to redeem a game or DLC',
        'vuln_exec_label'   => 'Product Key',
        'vuln_exec_placeholder' => 'e.g. GAMER2026',
        'vuln_exec_hint'    => 'Try: GAMER2026, INDIE25, STEAM10, DLCFREE',

        'vuln_upload_title' => 'Profile Avatar Upload',
        'vuln_upload_desc'  => 'Upload your gaming avatar or profile picture',

        'vuln_csrf_title'   => 'Account Security',
        'vuln_csrf_desc'    => 'Change your Flux account password',

        'vuln_fi_title'     => 'Store Policies',
        'vuln_fi_desc'      => 'Read our refund policy, EULA, and FAQs',

        'vuln_brute_title'  => 'Developer Console',
        'vuln_brute_desc'   => 'Login to access developer tools and beta keys',
        'vuln_brute_success'=> 'Welcome to Developer Mode',
        'vuln_brute_flavor' => 'You now have access to dev builds and cheat codes.',

        'about_what'        => 'Flux is a deliberately vulnerable web application themed as a video game storefront (think Steam, but less secure).',
        'about_modules'     => [
            ['Game Lookup',      'Search games by title',       'SQL Injection'],
            ['Store Search',     'Search the catalog',          'XSS (Reflected)'],
            ['Game Reviews',     'Community reviews',           'XSS (Stored)'],
            ['Key Redeemer',     'Validate product keys',       'Command Injection'],
            ['Avatar Upload',    'Upload profile pic',          'File Upload'],
            ['Account Security', 'Change password',             'CSRF'],
            ['Store Policies',   'View store pages',            'File Inclusion'],
            ['Dev Console',      'Developer login',             'Brute Force'],
        ],
    ],

    /* ================================================================
       CARS — $1000-or-less death traps for high school students
       ================================================================ */
    'cars' => [
        'name'        => 'Rust Bucket Motors',
        'tagline'     => 'Vulnerable Used Car Lot',
        'motto'       => '"If the check engine light ISN\'T on, something is wrong."',
        'login_quip'  => '"Our security is as reliable as our transmissions."',
        'icon'        => 'fas fa-car-crash',
        'accent'      => '#ef4444',        // red (danger!)
        'accent_dark' => '#b91c1c',
        'accent_light'=> '#f87171',
        'accent_glow' => 'rgba(239,68,68,0.25)',
        'secondary'   => '#eab308',        // yellow (warning!)
        'css_class'   => 'theme-cars',

        'item_label'      => 'Vehicle',
        'item_label_plural'=> 'Vehicles',
        'venue_label'     => 'Lot Location',
        'date_label'      => 'Listed',
        'price_label'     => 'Price',
        'genre_label'     => 'Type',
        'link_label'      => 'Carfax',
        'link_icon'       => 'fas fa-file-alt',
        'link_color'      => '#f59e0b',
        'card_icon'       => 'fas fa-car',
        'poster_icon'     => 'fas fa-car',

        'dash_title'      => 'Today\'s "Finest" Inventory',
        'dash_subtitle'   => 'All vehicles $1,000 or less &mdash; "runs" and "drives" are used loosely here',
        'dash_icon'       => 'fas fa-fire-alt',

        'nav_section'     => 'Lot',
        'nav_dashboard'   => 'Inventory',

        'vuln_sqli_title'   => 'Vehicle Search',
        'vuln_sqli_desc'    => 'Look up vehicles by name',
        'vuln_sqli_label'   => 'Vehicle Name',
        'vuln_sqli_placeholder' => 'Enter vehicle (e.g. Civic)',
        'vuln_sqli_icon'    => 'fas fa-database',

        'vuln_xss_r_title'  => 'Vehicle Search',
        'vuln_xss_r_desc'   => 'Search our vast lot of questionable vehicles',
        'vuln_xss_r_placeholder' => 'Search vehicles, makes, models...',

        'vuln_xss_s_title'  => 'Customer Reviews',
        'vuln_xss_s_desc'   => 'Share your experience owning one of our "fine" vehicles',
        'vuln_xss_s_name_placeholder' => 'Your name',
        'vuln_xss_s_msg_placeholder'  => 'Still running? Let us know...',

        'vuln_exec_title'   => 'Warranty Check',
        'vuln_exec_desc'    => 'Enter your warranty code (ha, good luck)',
        'vuln_exec_label'   => 'Warranty Code',
        'vuln_exec_placeholder' => 'e.g. NOTACHANCE',
        'vuln_exec_hint'    => 'Try: NOTACHANCE, GOODLUCK, DUCTAPE, PRAYERS',

        'vuln_upload_title' => 'Upload Vehicle Photo',
        'vuln_upload_desc'  => 'Upload a photo of your Rust Bucket purchase (for insurance claims)',

        'vuln_csrf_title'   => 'Account Security',
        'vuln_csrf_desc'    => 'Change your Rust Bucket Motors account password',

        'vuln_fi_title'     => '"Warranty" & Policies',
        'vuln_fi_desc'      => 'Read our generous policies (spoiler: everything is as-is)',

        'vuln_brute_title'  => 'Employee Break Room',
        'vuln_brute_desc'   => 'Login to access the employee-only vehicle history reports',
        'vuln_brute_success'=> 'Welcome to the Break Room',
        'vuln_brute_flavor' => 'You now have access to the REAL vehicle history reports.',

        'about_what'        => 'Rust Bucket Motors is a deliberately vulnerable web application themed as a hilariously sketchy used car lot selling $1,000-or-less vehicles to high school students.',
        'about_modules'     => [
            ['Vehicle Search',   'Search inventory by name',    'SQL Injection'],
            ['Lot Search',       'Search vehicles',             'XSS (Reflected)'],
            ['Customer Reviews', 'Community feedback',          'XSS (Stored)'],
            ['Warranty Check',   'Validate warranty codes',     'Command Injection'],
            ['Vehicle Photo',    'Upload vehicle pics',         'File Upload'],
            ['Account Security', 'Change password',             'CSRF'],
            ['Policies',         'View policy pages',           'File Inclusion'],
            ['Break Room',       'Employee login',              'Brute Force'],
        ],
    ],
];

/**
 * Get the current theme key from session/cookie.
 * Returns null if no theme has been chosen yet.
 */
function getCurrentTheme() {
    if (isset($_SESSION['theme']) && isset($GLOBALS['THEMES'][$_SESSION['theme']])) {
        return $_SESSION['theme'];
    }
    if (isset($_COOKIE['theme']) && isset($GLOBALS['THEMES'][$_COOKIE['theme']])) {
        $_SESSION['theme'] = $_COOKIE['theme'];
        return $_COOKIE['theme'];
    }
    return null;
}

/**
 * Get the current theme config array.
 * Falls back to 'music' if nothing is set.
 */
function getThemeConfig($fallback = 'music') {
    global $THEMES;
    $key = getCurrentTheme() ?? $fallback;
    return $THEMES[$key] ?? $THEMES['music'];
}

/**
 * Set the active theme.
 */
function setTheme($key) {
    global $THEMES;
    if (!isset($THEMES[$key])) return false;
    $_SESSION['theme'] = $key;
    setcookie('theme', $key, time() + 3600 * 24 * 365, '/');
    $_COOKIE['theme'] = $key;
    return true;
}
