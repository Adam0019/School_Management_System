<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
include('../../Config/dbcon.php');

// Auth check
if (!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../../Login/logout.php');
    exit();
}

// Method check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: ../add_pages/add_class_page.php');
    exit();
}

// Check required fields
if (!isset($_POST['c_name']) || trim($_POST['c_name']) === '') {
    $_SESSION['error'] = 'Missing required fields!';
    header('Location: ../add_pages/add_class_page.php');
    exit();
}

// Sanitize inputs
$c_name    = trim($_POST['c_name']    ?? '');
$sub_id    = trim($_POST['sub_id']    ?? '');
$sub_one   = trim($_POST['sub_one']   ?? '');
$sub_two   = trim($_POST['sub_two']   ?? '');
$sub_three = trim($_POST['sub_three'] ?? '');
$sub_four  = trim($_POST['sub_four']  ?? '');
$sub_five  = trim($_POST['sub_five']  ?? '');
$sub_six   = trim($_POST['sub_six']   ?? '');
$sub_seven = trim($_POST['sub_seven'] ?? '');
$sub_eight = trim($_POST['sub_eight'] ?? '');
$sub_nine  = trim($_POST['sub_nine']  ?? ''); // Fixed: was ($_POST['sub_nine'] || '')
$section_a = trim($_POST['section_a'] ?? 'A'); // Default section
$section_b = trim($_POST['section_b'] ?? 'B');
$section_c = trim($_POST['section_c'] ?? 'C');
$section_d = trim($_POST['section_d'] ?? 'D');


// Basic validation
if (strlen($c_name) < 2) {
    $_SESSION['error'] = 'Class name must be at least 2 characters.';
    header('Location: ../add_pages/add_class_page.php');
    exit();
}

// Handle image upload
$imageName = 'class_default.jpg'; // Default image

if (isset($_FILES['c_img']) && $_FILES['c_img']['error'] === UPLOAD_ERR_OK) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize       = 2 * 1024 * 1024; // 2MB

    $filename  = $_FILES['c_img']['name'];
    $filesize  = $_FILES['c_img']['size'];
    $tmpPath   = $_FILES['c_img']['tmp_name'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Validate file size
    if ($filesize > $maxFileSize) {
        $_SESSION['error'] = 'Image file size must not exceed 2MB.';
        header('Location: ../add_pages/add_class_page.php');
        exit();
    }

    // Validate extension
    if (!in_array($extension, $allowedExtensions)) {
        $_SESSION['error'] = 'Invalid image format. Allowed: jpg, jpeg, png, gif.';
        header('Location: ../add_pages/add_class_page.php');
        exit();
    }

    // Validate actual MIME type (prevents disguised malicious files)
    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $tmpPath);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimeTypes)) {
        $_SESSION['error'] = 'Invalid image file. File content does not match its extension.';
        header('Location: ../add_pages/add_class_page.php');
        exit();
    }

    // Generate unique filename and move file
    $imageName  = 'class_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetPath = '../assets/image/' . $imageName;

    if (!move_uploaded_file($tmpPath, $targetPath)) {
        $_SESSION['error'] = 'Failed to upload image.';
        header('Location: ../add_pages/add_class_page.php');
        exit();
    }
}

// Insert into database
$sql = "INSERT INTO class_tbl 
            (c_name, sub_id, sub_one, sub_two, sub_three, sub_four, sub_five, sub_six, sub_seven, sub_eight, sub_nine, section_a, section_b, section_c, section_d, c_img) 
        VALUES 
            (:c_name, :sub_id, :sub_one, :sub_two, :sub_three, :sub_four, :sub_five, :sub_six, :sub_seven, :sub_eight, :sub_nine, :section_a, :section_b, :section_c, :section_d, :c_img)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':c_name',    $c_name);
    $stmt->bindParam(':sub_id',    $sub_id);
    $stmt->bindParam(':sub_one',   $sub_one);
    $stmt->bindParam(':sub_two',   $sub_two);
    $stmt->bindParam(':sub_three', $sub_three);
    $stmt->bindParam(':sub_four',  $sub_four);
    $stmt->bindParam(':sub_five',  $sub_five);
    $stmt->bindParam(':sub_six',   $sub_six);
    $stmt->bindParam(':sub_seven', $sub_seven);
    $stmt->bindParam(':sub_eight', $sub_eight);
    $stmt->bindParam(':sub_nine',  $sub_nine);
    $stmt->bindParam(':section_a', $section_a);
    $stmt->bindParam(':section_b', $section_b);
    $stmt->bindParam(':section_c', $section_c);
    $stmt->bindParam(':section_d', $section_d);
    $stmt->bindParam(':c_img',     $imageName);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Class added successfully!';
    } else {
        $_SESSION['error'] = 'Failed to add class.';
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error adding class: ' . $e->getMessage();
}

// Always redirect after POST
header('Location: ../add_pages/add_class_page.php');
exit();