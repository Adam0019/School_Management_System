<!-- // error_reporting(E_ALL);
// ini_set('display_errors', 1); -->

<?php
session_start();
include('../../Config/dbcon.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Check required fields
    if (!isset($_POST['sub_name'])) {
        $_SESSION['error'] = 'Missing required fields!';
        header('Location: ../add_pages/add_subject_page.php');
        exit;
    }

    $sub_name = trim($_POST['sub_name']);
    
    // Basic server-side validation
    if(strlen($sub_name) < 2) {
        $_SESSION['error'] = 'Subject Name must be at least 2 characters.';
        header('Location: ../add_pages/add_subject_page.php');
        exit;
    }

    $sub_about = isset($_POST['sub_about']) ? trim($_POST['sub_about']) : '';
    
    // Initialize image name with default
    $imageName = 'subject_default.jpg';
    
    // Handle image upload
    if(isset($_FILES['sub_img']) && $_FILES['sub_img']['error'] == 0){
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['sub_img']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)){
            // Generate unique filename
          $imageName = time() . '_' . basename($filename);
          $upload_path = '../assets/image/' . $imageName;
            

            if(!move_uploaded_file($_FILES['sub_img']['tmp_name'], $upload_path)){
                $_SESSION['error'] = 'Failed to upload image.';
                header('Location: ../add_pages/add_subject_page.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Invalid image format. Allowed formats: jpg, jpeg, png, gif.';
            header('Location: ../add_pages/add_subject_page.php');
        }
    }
    
    // Insert into database
    $INSERT = "INSERT INTO subject_tbl (sub_name, sub_img, sub_about) VALUES (:sub_name, :sub_img, :sub_about)";
    $stmt = $pdo->prepare($INSERT);
    $stmt->bindParam(':sub_name', $sub_name);
    $stmt->bindParam(':sub_img', $imageName);
    $stmt->bindParam(':sub_about', $sub_about);
    
    if($stmt->execute()){
        $_SESSION['success'] = 'Subject added successfully!';
        header('Location: ../pages/subjects_page.php');
        exit;
    } else {
        $_SESSION['error'] = 'Failed to add subject. Please try again.';
        header('Location: ../add_pages/add_subject_page.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: ../add_pages/add_subject_page.php');
    exit;
}
?>