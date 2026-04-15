<?php
// Uncomment below lines for debugging only — remove in production
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
include('../../Config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ── Check required fields ──────────────────────────────────────────
    if (!isset($_POST['s_name'])   || !isset($_POST['s_g_name']) ||
        !isset($_POST['s_phone'])  || !isset($_POST['s_class'])  ||
        !isset($_POST['s_roll'])) {
        $_SESSION['error'] = 'Missing required fields!';
        header('Location: ../add_pages/add_student_page.php');
        exit;
    }

    // ── Sanitize inputs ────────────────────────────────────────────────
    $s_name    = trim($_POST['s_name']);
    $s_gender  = isset($_POST['s_gender'])  ? trim($_POST['s_gender'])  : '';
    $s_dob     = isset($_POST['s_dob'])     ? trim($_POST['s_dob'])     : '';
    $s_g_name  = trim($_POST['s_g_name']);
    $s_g_type  = isset($_POST['s_g_type'])  ? trim($_POST['s_g_type'])  : '';
    $s_phone   = trim($_POST['s_phone']);
    $s_address = isset($_POST['s_address']) ? trim($_POST['s_address']) : '';
    $c_id      = trim($_POST['s_class']);   // c_id sent directly from the class dropdown
    $s_roll    = trim($_POST['s_roll']);
    $s_section = isset($_POST['s_section']) ? trim($_POST['s_section']) : '';

    // ── Validate c_id and fetch the matching class name ────────────────
    // Trust c_id from the form, but verify it actually exists in the DB.
    // The class name is resolved from the DB — not blindly trusted from POST.
    $classQuery = "SELECT c_id, c_name FROM class_tbl WHERE c_id = :c_id LIMIT 1";
    $stmt = $pdo->prepare($classQuery);
    $stmt->bindParam(':c_id', $c_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $classRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $s_class  = $classRow['c_name'];
    } else {
        $_SESSION['error'] = 'Invalid class selected!';
        header('Location: ../add_pages/add_student_page.php');
        exit;
    }
    

    // ── Server-side validation ─────────────────────────────────────────
    if (strlen($s_name) < 2) {
        $_SESSION['error'] = 'Name must be at least 2 characters.';
        header('Location: ../add_pages/add_student_page.php');
        exit;
    }

    if (strlen($s_g_name) < 2) {
        $_SESSION['error'] = 'Guardian Name must be at least 2 characters.';
        header('Location: ../add_pages/add_student_page.php');
        exit;
    }


    if (!preg_match('/^\d{1,10}$/', $s_roll)) {
        $_SESSION['error'] = 'Roll number must be numeric and up to 10 digits.';
        header('Location: ../add_pages/add_student_page.php');
        exit;
    }

    $s_phone_digits = preg_replace('/\D/', '', $s_phone);
    if (strlen($s_phone_digits) < 10) {
        $_SESSION['error'] = 'Phone number must be at least 10 digits.';
        header('Location: ../add_pages/add_student_page.php');
        exit;
    }

    // ── Default image based on gender ─────────────────────────────────
    $imageName = 'default.jpg';
    if ($s_gender === 'Male') {
        $imageName = 'male_default.jpeg';
    } elseif ($s_gender === 'Female') {
        $imageName = 'female_default.jpeg';
    }

    // ── Handle image upload ────────────────────────────────────────────
    if (isset($_FILES['s_img']) && $_FILES['s_img']['error'] == 0) {
        $allowed  = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['s_img']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($filetype, $allowed)) {
            $imageName   = time() . '_' . basename($filename);
            $upload_path = '../assets/image/' . $imageName;

            if (!move_uploaded_file($_FILES['s_img']['tmp_name'], $upload_path)) {
                $_SESSION['error'] = 'Failed to upload image!';
                header('Location: ../add_pages/add_student_page.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Invalid file type! Only JPG, JPEG, PNG, and GIF are allowed.';
            header('Location: ../add_pages/add_student_page.php');
            exit;
        }
    }

    // ── Generate credentials ───────────────────────────────────────────
    $username = $s_class . '_' . $s_roll;


    $password_hash = password_hash($s_dob, PASSWORD_DEFAULT);

    // ── Insert into database ───────────────────────────────────────────
    
    $sql = "INSERT INTO student_tbl 
                (s_name, s_gender, s_dob, s_g_name, s_g_type, s_phone,
                 s_address, c_id, s_class, s_roll, s_section, s_img, username, password)
            VALUES 
                (:s_name, :s_gender, :s_dob, :s_g_name, :s_g_type, :s_phone,
                 :s_address, :c_id, :s_class, :s_roll, :s_section, :s_img, :username, :password_hash)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':s_name',        $s_name);
        $stmt->bindParam(':s_gender',      $s_gender);
        $stmt->bindParam(':s_dob',         $s_dob);
        $stmt->bindParam(':s_g_name',      $s_g_name);
        $stmt->bindParam(':s_g_type',      $s_g_type);
        $stmt->bindParam(':s_phone',       $s_phone);
        $stmt->bindParam(':s_address',     $s_address);
        $stmt->bindParam(':c_id',          $c_id);
        $stmt->bindParam(':s_class',       $s_class);
        $stmt->bindParam(':s_roll',        $s_roll);
        $stmt->bindParam(':s_section',     $s_section);
        $stmt->bindParam(':s_img',         $imageName);
        $stmt->bindParam(':username',      $username);
        $stmt->bindParam(':password_hash', $password_hash); // matches SQL placeholder

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Student added successfully!';
            header('Location: ../pages/students_page.php'); // redirect to list, not back to add form
            exit;
        } else {
            $_SESSION['error'] = 'Failed to add student!';
            header('Location: ../add_pages/add_student_page.php');
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        header('Location: ../add_pages/add_student_page.php');
        exit;
    }

} else {
    header('Location: ../add_pages/add_student_page.php');
    exit;
}
?>