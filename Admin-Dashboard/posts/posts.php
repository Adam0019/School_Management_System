<?php
include('../Include/header.php');
// Authentication check - redirect if not logged in
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}

include('../Include/sidebar.php');
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content profile-page">
    <div class="page-header">
        <h2>List of Articles</h2>
    </div>
 <div class="button-container">
  <div class="add_btn" >
       <a href="../sub_category/add_sub_category.php" class="btn btn-primary ">
            <i class="fas fa-plus"></i> Add Sub Category
        </a>
    </div>
    
    <div class="add_btn">
         <a href="../category/add_category.php" class="btn btn-primary ">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>
   
   <div class="add_btn">
        <a href="../posts/add_article.php" class="btn btn-primary ">
            <i class="fas fa-plus"></i> Add Article
        </a>
    </div>
    </div>
    
    <div class="profile-section">
    </div>
</main>

<?php include('../Include/footer.php'); ?>