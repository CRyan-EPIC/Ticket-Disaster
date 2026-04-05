<?php
// SQL Injection - Medium Security Level
// Uses htmlspecialchars() which does NOT protect against SQL injection
// (single quotes are not escaped by default)
// Input comes from a dropdown, but you can intercept the POST request

if (isset($_POST['Submit'])) {
    $name = $_POST['id'];
    $theme = getThemeConfig();

    $conn = dvwaDbConnect();

    // Wrong protection: htmlspecialchars() escapes < > & " but NOT single quotes
    $name = htmlspecialchars($name);

    // Vulnerable: $name still injectable with single quotes
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
        $html .= "<span style='color: #ef4444;'>No results found.</span>";
    }

    $conn->close();
}
