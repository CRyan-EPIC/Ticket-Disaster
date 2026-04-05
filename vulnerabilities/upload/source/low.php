<?php
// File Upload - Low Security Level
// No validation whatsoever - accepts any file type

if (isset($_POST['Upload'])) {
    $target_path  = "../../hackable/uploads/";
    $target_path .= basename($_FILES['uploaded']['name']);

    // Vulnerable: no file type, size, or extension check
    if (move_uploaded_file($_FILES['uploaded']['tmp_name'], $target_path)) {
        $html .= "<p class='text-success'><i class='fas fa-check-circle'></i> File uploaded successfully!</p>";
        $html .= "<p>Saved to: <code>{$target_path}</code></p>";
    } else {
        $html .= "<p class='text-danger'>Upload failed.</p>";
    }
}
