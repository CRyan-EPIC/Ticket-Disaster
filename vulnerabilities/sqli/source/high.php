<?php
// SQL Injection - High Security Level
// Input via session variable (set from popup), LIMIT 1 added
// Still vulnerable to injection through the popup

if (isset($_SESSION['id'])) {
    $name = $_SESSION['id'];
    $theme = getThemeConfig();

    $conn = dvwaDbConnect();

    // Vulnerable: still string-interpolated, just harder to exploit
    $query = "SELECT band_name, venue FROM concerts WHERE band_name = '$name' LIMIT 1;";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= "<div style='margin: 0.5rem 0;'>";
            $html .= htmlspecialchars($theme['item_label']) . ": {$row['band_name']}<br>";
            $html .= htmlspecialchars($theme['venue_label']) . ": {$row['venue']}";
            $html .= "</div><hr style='border-color: #333;'>";
        }
    } else {
        $html .= "<span style='color: #ef4444;'>No results found.</span>";
    }

    $conn->close();
}
