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
            <h2>Add New Sub Category</h2>
            <p>Fill in the details below to add a new sub category to the system</p>
            
        </div>

        <?php
// Fetch categories
try {
    $query = "SELECT * FROM category_tbl";
    $stmt  = $pdo->prepare($query);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . htmlspecialchars($e->getMessage());
    $categories = [];
}

        ?>

        <form action="../sub_category/store_sub_category.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <!-- Sub Category Name -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-tag"></i>
                        Sub Category Name
                    </label>
                    <input type="text" name="sub_cat_name" id="sub_cat_name" class="field-input" placeholder="Enter sub category name" required>
                </div>
            </div>

                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-list"></i>
                        Select Category
                    </label>
                    <select name="cat_id" id="cat_id" class="field-select" required>
                        <option value="">-- Select a Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['cat_id']); ?>">
                                <?php echo htmlspecialchars($category['cat_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            <div class="form-actions">
                <a href="add_sub_category.php">
                <button type="button" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </button></a>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Sub Category
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
      
