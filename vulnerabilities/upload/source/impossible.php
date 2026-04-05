<?php
// File Upload - Impossible Security Level
// Validates extension, MIME, size, re-creates image with GD, renames file

if (isset($_POST['Upload'])) {
    if (!checkToken($_POST['user_token'] ?? '', $_SESSION['session_token'] ?? '')) {
        $html .= "<p class='text-danger'>Invalid CSRF token.</p>";
    } else {
        $uploaded_name = $_FILES['uploaded']['name'];
        $uploaded_ext  = strtolower(substr($uploaded_name, strrpos($uploaded_name, '.') + 1));
        $uploaded_size = $_FILES['uploaded']['size'];
        $uploaded_type = $_FILES['uploaded']['type'];
        $uploaded_tmp  = $_FILES['uploaded']['tmp_name'];

        // Strict checks
        if (($uploaded_ext == "jpg" || $uploaded_ext == "jpeg" || $uploaded_ext == "png") &&
            ($uploaded_size < 100000) &&
            ($uploaded_type == "image/jpeg" || $uploaded_type == "image/png") &&
            getimagesize($uploaded_tmp)) {

            // Re-create the image to strip any embedded code
            if ($uploaded_type == "image/jpeg") {
                $img = imagecreatefromjpeg($uploaded_tmp);
                $new_name = md5(uniqid()) . '.jpg';
                imagejpeg($img, "../../hackable/uploads/" . $new_name, 90);
            } else {
                $img = imagecreatefrompng($uploaded_tmp);
                $new_name = md5(uniqid()) . '.png';
                imagepng($img, "../../hackable/uploads/" . $new_name, 9);
            }
            imagedestroy($img);

            $html .= "<p class='text-success'><i class='fas fa-check-circle'></i> File uploaded and processed successfully!</p>";
            $html .= "<p>Saved as: <code>hackable/uploads/{$new_name}</code></p>";
        } else {
            $html .= "<p class='text-danger'>Only valid JPG/PNG images under 100KB are allowed.</p>";
        }
    }
    generateSessionToken();
}
