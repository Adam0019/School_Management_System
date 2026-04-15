<?php
session_start();
include('../../Config/dbcon.php');

// ─── Authentication Check ────────────────────────────────────────────────────
if (empty($_SESSION['userAuth'])) {
    header('Location: ../../Login/logout.php');
    exit();
}

// ─── Request Method Check ────────────────────────────────────────────────────
if (!isset($_POST['submit'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../pages/subjects_page.php");
    exit();
}

// ─── CSRF Protection ─────────────────────────────────────────────────────────
if (
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
) {
    $_SESSION['error'] = "Invalid CSRF token. Please try again.";
    header("Location: ../pages/subjects_page.php");
    exit();
}

// ─── Input Validation ────────────────────────────────────────────────────────
$sub_id   = filter_input(INPUT_POST, 'sub_id',   FILTER_VALIDATE_INT);
$sub_name = trim($_POST['sub_name']  ?? '');
$sub_about= trim($_POST['sub_about'] ?? '');

if (!$sub_id || $sub_id <= 0) {
    $_SESSION['error'] = "Invalid subject ID.";
    header("Location: ../pages/subjects_page.php");
    exit();
}

if ($sub_name === '') {
    $_SESSION['error'] = "Subject name cannot be empty.";
    header("Location: edit_subject_profile.php?sub_id=$sub_id");
    exit();
}

if (mb_strlen($sub_name) > 255) {
    $_SESSION['error'] = "Subject name is too long (max 255 characters).";
    header("Location: edit_subject_profile.php?sub_id=$sub_id");
    exit();
}

try {
    // ─── Fetch Current Record ─────────────────────────────────────────────────
    $stmt_fetch = $pdo->prepare(
        "SELECT sub_id, sub_name, sub_about, sub_img FROM subject_tbl WHERE sub_id = ?"
    );
    $stmt_fetch->execute([$sub_id]);
    $currentData = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

    if (!$currentData) {
        $_SESSION['error'] = "Subject not found.";
        header("Location: ../pages/subjects_page.php");
        exit();
    }

    // ─── Authorization Check ──────────────────────────────────────────────────
    // Uncomment and adjust if subjects are owned by specific users:
    // if ($currentData['owner_id'] !== $_SESSION['user_id']) {
    //     $_SESSION['error'] = "You are not authorized to edit this subject.";
    //     header("Location: ../pages/subjects_page.php");
    //     exit();
    // }

    $current_image = $currentData['sub_img'];
    $upload_dir    = '../assets/image/';
    $image_name    = $current_image; // default: keep existing image
    $imageChanged  = false;

    // ─── Handle Image Upload ──────────────────────────────────────────────────
    if (isset($_FILES['sub_img']) && $_FILES['sub_img']['error'] !== UPLOAD_ERR_NO_FILE) {

        $file = $_FILES['sub_img'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error. Please try again.');
        }

        $allowed_ext  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_mime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validate extension
        if (!in_array($file_ext, $allowed_ext, true)) {
            throw new Exception('Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.');
        }

        // Validate MIME type using finfo (prevents spoofed extensions)
        $finfo     = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($file['tmp_name']);
        if (!in_array($mime_type, $allowed_mime, true)) {
            throw new Exception('Invalid file content. Please upload a valid image.');
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('File size exceeds the maximum limit of 5MB.');
        }

        // Generate a safe, unique filename
        $new_file_name = 'subject_' . $sub_id . '_' . time() . '.' . $file_ext;
        $upload_path   = $upload_dir . $new_file_name;

        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception('Failed to upload image. Please check directory permissions.');
        }

        $image_name   = $new_file_name;
        $imageChanged = true;
    }

    // ─── Update Database ──────────────────────────────────────────────────────
    $stmt_update = $pdo->prepare(
        "UPDATE subject_tbl SET sub_name = ?, sub_about = ?, sub_img = ? WHERE sub_id = ?"
    );
    $stmt_update->execute([$sub_name, $sub_about, $image_name, $sub_id]);

    // ─── Delete Old Image (only after successful DB update) ───────────────────
    if ($imageChanged && !empty($current_image) && $current_image !== 'subject_default.jpg') {
        $old_image_path = $upload_dir . $current_image;
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
    }

    // ─── Success Message ──────────────────────────────────────────────────────
    $dataChanged = (
        $currentData['sub_name']  !== $sub_name  ||
        $currentData['sub_about'] !== $sub_about ||
        $imageChanged
    );

    $_SESSION['success'] = $dataChanged
        ? "Subject profile updated successfully."
        : "No changes were made to the subject profile.";

    header("Location: edit_subject_profile.php?sub_id=$sub_id");
    exit();

} catch (Exception $e) {
    // If upload succeeded but something else failed, clean up the newly uploaded file
    if ($imageChanged && !empty($new_file_name) && file_exists($upload_dir . $new_file_name)) {
        unlink($upload_dir . $new_file_name);
    }

    $_SESSION['error'] = $e->getMessage();
    header("Location: edit_subject_profile.php?sub_id=$sub_id");
    exit();
}
