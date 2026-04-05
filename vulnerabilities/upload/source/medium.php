<?php
// File Upload - Medium Security Level
// Checks MIME type but it can be spoofed in the request

if (isset($_POST['Upload'])) {
    $target_path  = "../../hackable/uploads/";
    $target_path .= basename($_FILES['uploaded']['name']);
    $uploaded_type = $_FILES['uploaded']['type'];
    $uploaded_size = $_FILES['uploaded']['size'];

    // Vulnerable: relies on client-supplied Content-Type header
    if (($uploaded_type == "image/jpeg" || $uploaded_type == "image/png") && ($uploaded_size < 100000)) {
        if (move_uploaded_file($_FILES['uploaded']['tmp_name'], $target_path)) {
            $html .= "<p class='text-success'><i class='fas fa-check-circle'></i> File uploaded successfully!</p>";
            $html .= "<p>Saved to: <code>{$target_path}</code></p>";
        } else {
            $html .= "<p class='text-danger'>Upload failed.</p>";
        }
    } else {
        $html .= "<p class='text-danger'>Only JPEG or PNG images under 100KB are allowed.</p>";
    }
}
