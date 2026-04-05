<?php
// XSS Reflected - Low Security Level
// Searches real concerts AND reflects input without encoding

if (isset($_GET['name']) && $_GET['name'] !== '') {
    $search = $_GET['name'];

    // Vulnerable: reflected directly without encoding
    $html .= '<div style="margin-bottom:0.5rem;">Search results for: <strong>' . $search . '</strong></div>';

    // But also search real concerts
    $conn = dvwaDbConnect();
    $escaped = mysqli_real_escape_string($conn, $search);
    $query = "SELECT * FROM concerts WHERE band_name LIKE '%{$escaped}%' OR venue LIKE '%{$escaped}%' OR city LIKE '%{$escaped}%' OR genre LIKE '%{$escaped}%' ORDER BY concert_date ASC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $html .= '<div class="search-results-grid">';
        while ($row = $result->fetch_assoc()) {
            $img = htmlspecialchars($row['poster_url'] ?? '');
            $hasImg = !empty($img) && file_exists('../../' . $img);
            $html .= '<div class="search-result-card">';
            if ($hasImg) {
                $html .= '<img src="../../' . $img . '" alt="">';
            } else {
                $html .= '<div class="sr-placeholder"><i class="fas fa-music"></i></div>';
            }
            $html .= '<div class="search-result-info">';
            $html .= '<div class="sr-band">' . htmlspecialchars($row['band_name']) . '</div>';
            $html .= '<div class="sr-venue"><i class="fas fa-map-marker-alt"></i> ' . htmlspecialchars($row['venue']) . ', ' . htmlspecialchars($row['city']) . '</div>';
            $html .= '<div class="sr-meta"><span class="sr-price">$' . number_format($row['price'], 0) . '</span> &middot; ' . date('M j', strtotime($row['concert_date'])) . '</div>';
            $html .= '</div></div>';
        }
        $html .= '</div>';
    } else {
        $html .= '<p style="color:var(--text-muted);margin-top:0.5rem;">No concerts found.</p>';
    }
    $conn->close();
}
