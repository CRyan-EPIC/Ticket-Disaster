<?php
// SQL Injection - Low Security Level
// No input validation or sanitization whatsoever

if (isset($_GET['Submit'])) {
    $name = $_REQUEST['id'];
    $theme = getThemeConfig();

    $conn = dvwaDbConnect();

    // Vulnerable: user input directly in query string
    $query = "SELECT band_name, venue FROM concerts WHERE band_name = '$name';";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= "<div style='margin: 0.5rem 0;'>";
            $html .= htmlspecialchars($theme['item_label']) . ": {$row['band_name']}<br>";
            $html .= htmlspecialchars($theme['venue_label']) . ": {$row['venue']}";
            $html .= "</div><hr style='border-color: #333;'>";
        }
    } else {
        $html .= "<span style='color: #ef4444;'>No results found for: " . htmlspecialchars($name) . "</span>";
    }

    $conn->close();
}
