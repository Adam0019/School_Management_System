<?php
session_start();
include('../../Config/dbcon.php');

// Authentication check
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL){
    header('Location: ../../Login/logout.php');
    exit();
}

if(isset($_POST['submit'])){

    // BUG FIX #1 — s_id was used raw with no validation. A missing or
    // non-numeric value would update the wrong row or cause an error.
    if(!isset($_POST['s_id']) || !is_numeric($_POST['s_id'])){
        $_SESSION['error'] = 'Invalid student ID.';
        header('Location: ../pages/students_page.php');
        exit();
    }

    try {
        $s_id      = intval($_POST['s_id']);
        $s_name    = trim($_POST['s_name']    ?? '');
        $s_gender  = trim($_POST['s_gender']  ?? '');
        $s_dob     = trim($_POST['s_dob']     ?? '');
        $s_g_name  = trim($_POST['s_g_name']  ?? '');
        $s_g_type  = trim($_POST['s_g_type']  ?? '');
        $s_phone   = trim($_POST['s_phone']   ?? '');
        $s_address = trim($_POST['s_address'] ?? '');
        $s_class   = trim($_POST['s_class']   ?? '');
        $s_roll    = trim($_POST['s_roll']    ?? '');
        $s_section = trim($_POST['s_section'] ?? '');

        // Fetch current row to preserve image and detect changes
        $stmt_fetch = $pdo->prepare("SELECT * FROM student_tbl WHERE s_id = ?");
        $stmt_fetch->execute([$s_id]);
        $currentData = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

        if(!$currentData){
            $_SESSION['error'] = 'Student not found.';
            header('Location: ../pages/students_page.php');
            exit();
        }

        $current_image = $currentData['s_img'];
        $image_name    = $current_image; // keep current image unless a new one is uploaded
        $imageChanged  = false;

        // ── Handle image upload ────────────────────────────────────────
        // BUG FIX #2 — upload path was ../../assets/image/ but the correct
        // path (confirmed earlier) is ../assets/image/
        if(isset($_FILES['s_img']) && $_FILES['s_img']['error'] == 0){
            $file      = $_FILES['s_img'];
            $file_ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed   = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if(in_array($file_ext, $allowed)){
                if($file['size'] <= 5242880){
                    $new_file_name = 'student_' . $s_id . '_' . time() . '.' . $file_ext;
                    $upload_dir    = '../assets/image/';
                    $upload_path   = $upload_dir . $new_file_name;

                    if(move_uploaded_file($file['tmp_name'], $upload_path)){
                        // Delete old image if not a default
                        if(!empty($current_image) && !in_array($current_image, ['male_default.jpeg', 'female_default.jpeg', 'default.jpg'])){
                            $old_path = $upload_dir . $current_image;
                            if(file_exists($old_path)) unlink($old_path);
                        }
                        $image_name   = $new_file_name;
                        $imageChanged = true;
                    } else {
                        $_SESSION['error'] = 'Failed to upload image.';
                        header("Location: edit_student_profile.php?s_id=$s_id");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'Image size must be less than 5MB.';
                    header("Location: edit_student_profile.php?s_id=$s_id");
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.';
                header("Location: edit_student_profile.php?s_id=$s_id");
                exit();
            }
        }

        // ── Update database ────────────────────────────────────────────
        $UPDATE = "UPDATE student_tbl SET
                       s_name     = ?,
                       s_gender   = ?,
                       s_dob      = ?,
                       s_g_name   = ?,
                       s_g_type   = ?,
                       s_phone    = ?,
                       s_address  = ?,
                       s_class    = ?,
                       s_roll     = ?,
                       s_section  = ?,
                       s_img      = ?
                   WHERE s_id = ?";

        $stmt = $pdo->prepare($UPDATE);
        $stmt->execute([
            $s_name, $s_gender, $s_dob, $s_g_name, $s_g_type,
            $s_phone, $s_address, $s_class, $s_roll, $s_section,
            $image_name, $s_id
        ]);

        // Detect whether anything actually changed
        $dataChanged = (
            $s_name    != $currentData['s_name']    ||
            $s_gender  != $currentData['s_gender']  ||
            $s_dob     != $currentData['s_dob']     ||
            $s_g_name  != $currentData['s_g_name']  ||
            $s_g_type  != $currentData['s_g_type']  ||
            $s_phone   != $currentData['s_phone']   ||
            $s_address != $currentData['s_address'] ||
            $s_class   != $currentData['s_class']   ||
            $s_roll    != $currentData['s_roll']    ||
            $s_section != $currentData['s_section']
        );

        $_SESSION[$dataChanged || $imageChanged ? 'success' : 'info'] =
            $dataChanged || $imageChanged
                ? 'Student profile updated successfully!'
                : 'No changes were made.';

        header("Location: edit_student_profile.php?s_id=$s_id");
        exit();

    } catch(PDOException $e){
        error_log("Error updating student: " . $e->getMessage());
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        header("Location: edit_student_profile.php?s_id=$s_id");
        exit();
    }

} else {
    header('Location: ../pages/students_page.php');
    exit();
}
?>