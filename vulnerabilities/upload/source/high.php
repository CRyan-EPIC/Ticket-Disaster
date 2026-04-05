<?php
// File Upload - High Security Level
// Checks file extension and uses getimagesize()
// Can be bypassed by embedding PHP in image metadata or using null bytes

if (isset($_POST['Upload'])) {
    $target_path  = "../../hackable/uploads/";
    $target_path .= basename($_FILES['uploaded']['name']);
    $uploaded_name = $_FILES['uploaded']['name'];
    $uploaded_ext  = strtolower(substr($uploaded_name, strrpos($uploaded_name, '.') + 1));
    $uploaded_size = $_FILES['uploaded']['size'];
    $uploaded_tmp  = $_FILES['uploaded']['tmp_name'];

    // Check extension
    if (($uploaded_ext == "jpg" || $uploaded_ext == "jpeg" || $uploaded_ext == "png") &&
        ($uploaded_size < 100000) &&
        getimagesize($uploaded_tmp)) {

        if (move_uploaded_file($uploaded_tmp, $target_path)) {
            $html .= "<p class='text-success'><i class='fas fa-check-circle'></i> File uploaded successfully!</p>";
            $html .= "<p>Saved to: <code>{$target_path}</code></p>";
        } else {
            $html .= "<p class='text-danger'>Upload failed.</p>";
        }
    } else {
        $html .= "<p class='text-danger'>Only valid JPG/PNG images under 100KB are allowed.</p>";
    }
}
