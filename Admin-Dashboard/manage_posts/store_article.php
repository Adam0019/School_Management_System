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
    header('Location: ../posts/add_article.php');
    exit();
}

// Check required fields
if (!isset($_POST['doc_title']) || trim($_POST['doc_title']) === '' || !isset($_POST['doc_cat_id']) || trim($_POST['doc_cat_id']) === '' || !isset($_POST['sub_cat_id']) || trim($_POST['sub_cat_id']) === '' || !isset($_POST['doc_about']) || trim($_POST['doc_about']) === '') {
    $_SESSION['error'] = 'Missing required fields!';
    header('Location: ../posts/add_article.php');
    exit();
}

// Sanitize inputs
$doc_title        = trim($_POST['doc_title'] ?? '');
$cat_id_input     = trim($_POST['doc_cat_id'] ?? '');
$cat_name         = trim($_POST['cat_name'] ?? '');
$sub_cat_id_input = trim($_POST['sub_cat_id'] ?? '');
$sub_cat_name     = trim($_POST['sub_cat_name'] ?? '');
$doc_about        = trim($_POST['doc_about'] ?? '');
$dop              = isset($_POST['dop']) ? trim($_POST['dop']) : '';
$doc_t_id         = trim($_POST['doc_t_id'] ?? '');
$author           = trim($_POST['doc_t_name'] ?? '');
$image_name       = 'article_default.jpg'; // Default image

// Handle image upload
if (isset($_FILES['doc_img']) && $_FILES['doc_img']['error'] == 0) {
    $allowed  = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['doc_img']['name'];
    $filetype = pathinfo($filename, PATHINFO_EXTENSION);

    if (in_array(strtolower($filetype), $allowed)) {
        // Generate unique filename
        $image_name  = time() . '_' . basename($filename);
        $upload_path = '../assets/image/' . $image_name;

        if (!move_uploaded_file($_FILES['doc_img']['tmp_name'], $upload_path)) {
            $_SESSION['error'] = 'Failed to upload image.';
            header('Location: ../posts/add_article.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Invalid image format. Allowed formats: jpg, jpeg, png, gif.';
        header('Location: ../posts/add_article.php');
        exit();
    }
}

// Basic validation
if (strlen($doc_title) < 5) {
    $_SESSION['error'] = 'Article title must be at least 5 characters.';
    header('Location: ../posts/add_article.php');
    exit();
}

if ($cat_id_input === 'add_new_cat' && strlen($cat_name) < 2) {
    $_SESSION['error'] = 'New category name must be at least 2 characters.';
    header('Location: ../posts/add_article.php');
    exit();
}

// FIX 3: changed 'add_new_sub_cat' → 'add_new_subcat' to match the form value in add_article.php
if ($sub_cat_id_input === 'add_new_subcat' && strlen($sub_cat_name) < 2) {
    $_SESSION['error'] = 'New sub-category name must be at least 2 characters.';
    header('Location: ../posts/add_article.php');
    exit();
}

try {
    $pdo->beginTransaction();

    // -----------------------------------------------------------------------
    // CATEGORY
    // -----------------------------------------------------------------------
    if ($cat_id_input === 'add_new_cat') {
        // FIX 1a: guard is already present; compute the new ID into $actual_cat_id
        if (empty($cat_name)) {
            throw new Exception('Category name is required when adding a new category.');
        }

        $stmt = $pdo->prepare("SELECT MAX(cat_id) as max_id FROM category_tbl WHERE cat_id REGEXP '^[0-9]+$'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // FIX 1b: assign to $actual_cat_id (not an unused $cat_id variable)
        $actual_cat_id = ($result['max_id'] ? intval($result['max_id']) + 1 : 1);

        $stmt = $pdo->prepare("INSERT INTO category_tbl (cat_id, cat_name) VALUES (:cat_id, :cat_name)");
        $stmt->bindParam(':cat_id',   $actual_cat_id);
        $stmt->bindParam(':cat_name', $cat_name);
        if (!$stmt->execute()) {
            throw new Exception('Failed to add new category.');
        }
    } else {
        // FIX 5a: use the existing category ID selected by the user
        $actual_cat_id = $cat_id_input;
    }

    // -----------------------------------------------------------------------
    // SUB-CATEGORY
    // -----------------------------------------------------------------------
    // FIX 3 (continued): check against 'add_new_subcat' to match the form value
    if ($sub_cat_id_input === 'add_new_subcat') {
        if (empty($sub_cat_name)) {
            throw new Exception('Sub-category name is required when adding a new sub-category.');
        }

        $stmt = $pdo->prepare("SELECT MAX(sub_cat_id) as max_id FROM sub_category_tbl WHERE sub_cat_id REGEXP '^[0-9]+$'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // FIX 2: assign to $actual_sub_cat_id (not an unused $sub_cat_id variable)
        $actual_sub_cat_id = ($result['max_id'] ? intval($result['max_id']) + 1 : 1);

        $stmt = $pdo->prepare("INSERT INTO sub_category_tbl (sub_cat_id, sub_cat_name, cat_id) VALUES (:sub_cat_id, :sub_cat_name, :cat_id)");
        $stmt->bindParam(':sub_cat_id',   $actual_sub_cat_id);
        $stmt->bindParam(':sub_cat_name', $sub_cat_name);
        $stmt->bindParam(':cat_id',       $actual_cat_id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to add new sub-category.');
        }
    } else {
        // FIX 5b: use the existing sub-category ID selected by the user
        $actual_sub_cat_id = $sub_cat_id_input;
    }

    // -----------------------------------------------------------------------
    // INSERT ARTICLE
    // -----------------------------------------------------------------------
    $sql  = "INSERT INTO doc_tbl (doc_title, doc_cat_id, cat_name, sub_cat_id, sub_cat_name, doc_about, doc_img, dop, doc_t_id, author)
             VALUES (:doc_title, :doc_cat_id, :cat_name, :sub_cat_id, :sub_cat_name, :doc_about, :doc_img, :dop, :doc_t_id, :doc_t_name)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':doc_title',   $doc_title);
    $stmt->bindParam(':doc_cat_id',  $actual_cat_id);
    $stmt->bindParam(':cat_name',    $cat_name);
    $stmt->bindParam(':sub_cat_id',  $actual_sub_cat_id);
    $stmt->bindParam(':sub_cat_name',$sub_cat_name);
    $stmt->bindParam(':doc_about',   $doc_about);
    $stmt->bindParam(':doc_img',     $image_name);
    $stmt->bindParam(':dop',         $dop);
    $stmt->bindParam(':doc_t_id',    $doc_t_id);
    $stmt->bindParam(':doc_t_name',  $author);

    if (!$stmt->execute()) {
        throw new Exception('Failed to add article. Please try again.');
    }

    // FIX 4: commit the transaction so all inserts are actually persisted
    $pdo->commit();

    $_SESSION['success'] = 'Article added successfully!';
    header('Location: ../posts/posts.php');
    exit();

} catch (Exception $e) {
    // Rolls back category/sub-category inserts if the article insert fails
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['error'] = 'Error adding article: ' . htmlspecialchars($e->getMessage());
    header('Location: ../posts/add_article.php');
    exit();
}