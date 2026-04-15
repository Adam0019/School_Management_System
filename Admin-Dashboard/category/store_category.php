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
if (!isset($_POST['cat_name']) || trim($_POST['cat_name']) === '') {
    $_SESSION['error'] = 'Missing required fields!';
    header('Location: ../category/add_category.php');
    exit();
}

// Sanitize inputs
$cat_name = trim($_POST['cat_name'] ?? '');

// Basic validation
if (strlen($cat_name) < 2) {
    $_SESSION['error'] = 'Category name must be at least 2 characters.';
    header('Location: ../category/add_category.php');
    exit();
}

try {
    $sql = "INSERT INTO category_tbl (cat_name) VALUES (:cat_name)";
    $stmt  = $pdo->prepare($sql);
    $stmt->bindParam(':cat_name', $cat_name);
    if($stmt->execute()){

        
        $_SESSION['success'] = 'Category added successfully!';
        header('Location: ../sub_category/add_sub_category.php');
        } else {
            $_SESSION['error'] = 'Failed to add category. Please try again.';
            header('Location: ../category/add_category.php');
        }
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error adding category: ' . htmlspecialchars($e->getMessage());
    header('Location: ../category/add_category.php');
    exit();
}