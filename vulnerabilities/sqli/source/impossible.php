<?php
// SQL Injection - Impossible Security Level
// Uses PDO prepared statements, anti-CSRF token, input validation

if (isset($_GET['Submit'])) {
    // Check anti-CSRF token
    if (!checkToken($_GET['user_token'] ?? '', $_SESSION['session_token'] ?? '')) {
        $html .= "<span style='color: #ef4444;'>Invalid CSRF token.</span>";
    } else {
        $name = $_GET['id'];
        $theme = getThemeConfig();

        $conn = dvwaDbConnect();

        // Secure: prepared statement with parameterized query
        $stmt = $conn->prepare("SELECT band_name, venue FROM concerts WHERE band_name = ? LIMIT 1");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            while ($row = $result->fetch_assoc()) {
                $html .= "<div style='margin: 0.5rem 0;'>";
                $html .= htmlspecialchars($theme['item_label']) . ": " . htmlspecialchars($row['band_name']) . "<br>";
                $html .= htmlspecialchars($theme['venue_label']) . ": " . htmlspecialchars($row['venue']);
                $html .= "</div>";
            }
        } else {
            $html .= "<span style='color: #ef4444;'>No results found.</span>";
        }

        $conn->close();
    }

    // Regenerate token
    generateSessionToken();
}
