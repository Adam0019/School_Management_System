<?php
session_start();
include('../../Config/dbcon.php');

// Authentication check
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL){
    header('Location: ../../Login/logout.php');
    exit();
}

if(isset($_POST['submit'])){

    // BUG FIX #1 — t_id was used raw with no validation. A missing or
    // non-numeric value would silently update the wrong row or cause an error.
    if(!isset($_POST['t_id']) || !is_numeric($_POST['t_id'])){
        $_SESSION['error'] = 'Invalid teacher ID.';
        header('Location: ../pages/teachers_page.php');
        exit();
    }

    try {
        $t_id    = intval($_POST['t_id']);
        $t_name  = trim($_POST['t_name']  ?? '');
        $t_gender = trim($_POST['t_gender'] ?? '');
        $t_dob   = trim($_POST['t_dob']   ?? '');
        $t_phone = trim($_POST['t_phone'] ?? '');
        $t_email = trim($_POST['t_email'] ?? '');
        $t_role  = trim($_POST['t_role']  ?? '');
        $t_address = trim($_POST['t_address'] ?? '');
        $t_about   = trim($_POST['t_about']   ?? '');

        // BUG FIX #2 — t_class, t_subject_main, t_subject_sec_1, t_subject_sec_2
        // were missing from the UPDATE entirely. Empty string converted to NULL
        // for FK safety (same pattern as store_teacher.php).
        $t_class         = !empty($_POST['t_class'])         ? trim($_POST['t_class'])         : null;
        $t_subject_main  = !empty($_POST['t_subject_main'])  ? trim($_POST['t_subject_main'])  : null;
        $t_subject_sec_1 = !empty($_POST['t_subject_sec_1']) ? trim($_POST['t_subject_sec_1']) : null;
        $t_subject_sec_2 = !empty($_POST['t_subject_sec_2']) ? trim($_POST['t_subject_sec_2']) : null;
        $t_sub_id        = $t_subject_main; // FK column mirrors main subject

        // Fetch current row to preserve image and detect changes
        $stmt_fetch = $pdo->prepare("SELECT * FROM teacher_tbl WHERE t_id = ?");
        $stmt_fetch->execute([$t_id]);
        $currentData = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

        if(!$currentData){
            $_SESSION['error'] = 'Teacher not found.';
            header('Location: ../pages/teachers_page.php');
            exit();
        }

        $current_image = $currentData['t_img'];
        $imageName     = $current_image; // keep current image unless a new one is uploaded
        $imageChanged  = false;

        // ── Handle image upload ────────────────────────────────────────
        // BUG FIX #3 — upload path was ../../assets/image/ but the correct
        // path (confirmed earlier) is ../assets/image/
        if(isset($_FILES['t_img']) && $_FILES['t_img']['error'] == 0){
            $allowed  = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['t_img']['name'];
            $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if(in_array($filetype, $allowed)){
                $imageName   = time() . '_' . basename($filename);
                $upload_path = '../assets/image/' . $imageName;

                if(move_uploaded_file($_FILES['t_img']['tmp_name'], $upload_path)){
                    // Delete old image if it's not a default
                    if($current_image && !in_array($current_image, ['male_default.jpeg', 'female_default.jpeg', 'default.jpg'])){
                        $old_file = '../assets/image/' . $current_image;
                        if(file_exists($old_file)){
                            unlink($old_file);
                        }
                    }
                    $imageChanged = true;
                } else {
                    $_SESSION['error'] = 'Failed to upload image!';
                    header('Location: edit_teacher_profile.php?t_id=' . $t_id);
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid file type! Only JPG, JPEG, PNG, and GIF allowed.';
                header('Location: edit_teacher_profile.php?t_id=' . $t_id);
                exit();
            }
        }

        // ── Update database ────────────────────────────────────────────
        $sql = "UPDATE teacher_tbl SET
                    t_name          = :t_name,
                    t_gender        = :t_gender,
                    t_dob           = :t_dob,
                    t_phone         = :t_phone,
                    t_email         = :t_email,
                    t_role          = :t_role,
                    t_class         = :t_class,
                    t_sub_id        = :t_sub_id,
                    t_subject_main  = :t_subject_main,
                    t_subject_sec_1 = :t_subject_sec_1,
                    t_subject_sec_2 = :t_subject_sec_2,
                    t_address       = :t_address,
                    t_about         = :t_about,
                    t_img           = :t_img
                WHERE t_id = :t_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':t_name',          $t_name);
        $stmt->bindParam(':t_gender',        $t_gender);
        $stmt->bindParam(':t_dob',           $t_dob);
        $stmt->bindParam(':t_phone',         $t_phone);
        $stmt->bindParam(':t_email',         $t_email);
        $stmt->bindParam(':t_role',          $t_role);
        $stmt->bindParam(':t_class',         $t_class);
        $stmt->bindParam(':t_sub_id',        $t_sub_id);
        $stmt->bindParam(':t_subject_main',  $t_subject_main);
        $stmt->bindParam(':t_subject_sec_1', $t_subject_sec_1);
        $stmt->bindParam(':t_subject_sec_2', $t_subject_sec_2);
        $stmt->bindParam(':t_address',       $t_address);
        $stmt->bindParam(':t_about',         $t_about);
        $stmt->bindParam(':t_img',           $imageName);
        $stmt->bindParam(':t_id',            $t_id);

        if($stmt->execute()){
            $dataChanged = (
                $t_name          != $currentData['t_name']          ||
                $t_gender        != $currentData['t_gender']        ||
                $t_dob           != $currentData['t_dob']           ||
                $t_phone         != $currentData['t_phone']         ||
                $t_email         != $currentData['t_email']         ||
                $t_role          != $currentData['t_role']          ||
                $t_class         != $currentData['t_class']         ||
                $t_subject_main  != $currentData['t_subject_main']  ||
                $t_subject_sec_1 != $currentData['t_subject_sec_1'] ||
                $t_subject_sec_2 != $currentData['t_subject_sec_2'] ||
                $t_address       != $currentData['t_address']       ||
                $t_about         != $currentData['t_about']
            );

            $_SESSION[$dataChanged || $imageChanged ? 'success' : 'info'] =
                $dataChanged || $imageChanged
                    ? 'Teacher profile updated successfully!'
                    : 'No changes were made.';

            header('Location: edit_teacher_profile.php?t_id=' . $t_id);
            exit();
        } else {
            $_SESSION['error'] = 'Failed to update teacher profile.';
            header('Location: edit_teacher_profile.php?t_id=' . $t_id);
            exit();
        }

    } catch(PDOException $e){
        error_log("Error updating teacher: " . $e->getMessage());
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        header('Location: edit_teacher_profile.php?t_id=' . $t_id);
        exit();
    }

} else {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: ../pages/teachers_page.php');
    exit();
}
?>