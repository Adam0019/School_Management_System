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
    header('Location: ../sub_category/add_sub_category.php');
    exit();
}
// Check required fields
if (!isset($_POST['sub_cat_name']) || trim($_POST['sub_cat_name']) === '' || !isset($_POST['cat_id']) || trim($_POST['cat_id']) === '') {
    $_SESSION['error'] = 'Missing required fields!';
    header('Location: ../sub_category/add_sub_category.php');
    exit();
}

// Sanitize inputs
$sub_cat_name = trim($_POST['sub_cat_name'] ?? '');
$cat_id       = trim($_POST['cat_id'] ?? '');

// Basic validation
if (strlen($sub_cat_name) < 2) {
    $_SESSION['error'] = 'Sub category name must be at least 2 characters.';
    header('Location: ../sub_category/add_sub_category.php');
    exit();
}

try {
    $sql = "INSERT INTO sub_category_tbl (sub_cat_name, cat_id) VALUES (:sub_cat_name, :cat_id)";
    $stmt  = $pdo->prepare($sql);
    $stmt->bindParam(':sub_cat_name', $sub_cat_name);
    $stmt->bindParam(':cat_id', $cat_id);
    if($stmt->execute()){

        
        $_SESSION['success'] = 'Sub category added successfully!';
        header('Location: ../posts/add_article.php');
        } else {
            $_SESSION['error'] = 'Failed to add sub category. Please try again.';
            header('Location: ../sub_category/add_sub_category.php');
        }
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error adding sub category: ' . htmlspecialchars($e->getMessage());
    header('Location: ../sub_category/add_sub_category.php');
    exit();
}

