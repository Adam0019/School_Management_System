<?php
include('../Include/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
include('../Include/sidebar.php');
$user=$_SESSION['u_name'];
?>
  <!-- ============ MAIN CONTENT SECTION ============ -->
    <main class="main-content">
        <div class="form-container">
             <a href="../pages/subjects_page.php" class="btn btn-primary ">
              <i class="fas fa-arrow-left"></i> Go Back
            </a>
        <div class="form-header">
            <h2>Add New Category</h2>
            <p>Fill in the details below to add a new category to the system</p>
            
        </div>

        <form action="../category/store_category.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <!-- Category Name -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-tag"></i>
                        Category Name
                    </label>
                    <input type="text" name="cat_name" id="cat_name" class="field-input" placeholder="Enter category name" required>
                </div>
            </div>
            <div class="form-actions">
                <a href="add_category.php">
                <button type="button" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </button></a>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Category
                </button>
            </div>
        </form>
    </div>
          </div>
    </main>
        <?php
        }
        include('../Include/footer.php');
        ?>
      