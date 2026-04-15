<?php
// Remove or disable these two lines in production
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
include('../../Config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ── Check required fields ──────────────────────────────────────────
    if (!isset($_POST['t_name']) || !isset($_POST['t_email']) ||
        !isset($_POST['t_phone']) || !isset($_POST['t_role'])) {
        $_SESSION['error'] = 'Missing required fields!';
        header('Location: ../add_pages/add_teacher_page.php');
        exit;
    }

    // ── Sanitize inputs ────────────────────────────────────────────────
    $t_name          = trim($_POST['t_name']);
    $t_gender        = isset($_POST['t_gender'])        ? trim($_POST['t_gender'])        : '';
    $t_dob           = isset($_POST['t_dob'])           ? trim($_POST['t_dob'])           : '';
    $t_phone         = trim($_POST['t_phone']);
    $t_email         = trim($_POST['t_email']);
    $t_role          = trim($_POST['t_role']);
    $t_address       = isset($_POST['t_address'])       ? trim($_POST['t_address'])       : '';
    $t_about         = isset($_POST['t_about'])         ? trim($_POST['t_about'])         : '';
    $t_subject_main  = isset($_POST['t_subject_main'])  ? trim($_POST['t_subject_main'])  : '';
    $t_subject_sec_1 = isset($_POST['t_subject_sec_1']) ? trim($_POST['t_subject_sec_1']) : '';
    $t_subject_sec_2 = isset($_POST['t_subject_sec_2']) ? trim($_POST['t_subject_sec_2']) : '';

    // t_class holds the numeric class ID — no separate c_id field in the form
    $t_class = isset($_POST['t_class']) ? trim($_POST['t_class']) : '';

    // t_subject_main already holds the numeric subject ID
    $sub_id = $t_subject_main;

    // ── Server-side validation ─────────────────────────────────────────
    if (strlen($t_name) < 2) {
        $_SESSION['error'] = 'Name must be at least 2 characters.';
        header('Location: ../add_pages/add_teacher_page.php');
        exit;
    }

    // Strip non-digits before length check
    $t_phone_digits = preg_replace('/\D/', '', $t_phone);
    if (strlen($t_phone_digits) < 10) {
        $_SESSION['error'] = 'Phone number must be at least 10 digits.';
        header('Location: ../add_pages/add_teacher_page.php');
        exit;
    }

    if (!filter_var($t_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: ../add_pages/add_teacher_page.php');
        exit;
    }

    // ── Default image based on gender ─────────────────────────────────
    $imageName = 'default.jpg';
    if ($t_gender === 'Male') {
        $imageName = 'male_default.jpeg';
    } elseif ($t_gender === 'Female') {
        $imageName = 'female_default.jpeg';
    }

    // ── Handle image upload ────────────────────────────────────────────
    if (isset($_FILES['t_img']) && $_FILES['t_img']['error'] == 0) {
        $allowed  = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['t_img']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($filetype, $allowed)) {
            $imageName   = time() . '_' . basename($filename);
            $upload_path = '../assets/image/' . $imageName;

            if (!move_uploaded_file($_FILES['t_img']['tmp_name'], $upload_path)) {
                $_SESSION['error'] = 'Failed to upload image.';
                header('Location: ../add_pages/add_teacher_page.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Invalid image format. Allowed: jpg, jpeg, png, gif.';
            header('Location: ../add_pages/add_teacher_page.php');
            exit;
        }
    }

    // ── Generate credentials ───────────────────────────────────────────
    $username = $t_email;
    $password = password_hash('Default@123', PASSWORD_BCRYPT);
    $t_key    = random_int(100000, 999999);

    // ── Insert into database ───────────────────────────────────────────
    // c_id removed — that column does not exist in teacher_tbl
    $sql = "INSERT INTO teacher_tbl 
                (t_name, t_gender, t_dob, t_phone, t_email, t_role,
                 t_c_id, t_class, t_sub_id, t_subject_main, t_subject_sec_1, t_subject_sec_2,
                 t_address, t_about, t_img, t_key, username, password)
            VALUES
                (:t_name, :t_gender, :t_dob, :t_phone, :t_email, :t_role,
                 :t_c_id, :t_class, :t_sub_id, :t_subject_main, :t_subject_sec_1, :t_subject_sec_2,
                 :t_address, :t_about, :t_img, :t_key, :username, :password)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':t_name',          $t_name);
        $stmt->bindParam(':t_gender',        $t_gender);
        $stmt->bindParam(':t_dob',           $t_dob);
        $stmt->bindParam(':t_phone',         $t_phone);
        $stmt->bindParam(':t_email',         $t_email);
        $stmt->bindParam(':t_role',          $t_role);
        $stmt->bindParam(':t_c_id',          $t_class);
        $stmt->bindParam(':t_class',         $t_class);
        $stmt->bindParam(':t_sub_id',         $sub_id);
        $stmt->bindParam(':t_subject_main',  $t_subject_main);
        $stmt->bindParam(':t_subject_sec_1', $t_subject_sec_1);
        $stmt->bindParam(':t_subject_sec_2', $t_subject_sec_2);
        $stmt->bindParam(':t_address',       $t_address);
        $stmt->bindParam(':t_about',         $t_about);
        $stmt->bindParam(':t_img',           $imageName);
        $stmt->bindParam(':t_key',           $t_key);
        $stmt->bindParam(':username',        $username);
        $stmt->bindParam(':password',        $password);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Teacher added successfully!';
            header('Location: ../pages/teachers_page.php');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to add teacher.';
            header('Location: ../add_pages/add_teacher_page.php');
            exit;
        }

    } catch (Exception $e) {
        error_log("Error adding teacher: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage(); // temporary — revert to generic message in production
        header('Location: ../add_pages/add_teacher_page.php');
        exit;
    }

} else {
    header('Location: ../add_pages/add_teacher_page.php');
    exit;
}
?>