<?php
include('../Include/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
include('../Include/sidebar.php');
$user=$_SESSION['u_name'];
?>
  <!-- ============ MAIN CONTENT SECTION ============ -->
    <main class="main-content">
        <div class="form-container">
             <a href="../posts/posts.php" class="btn btn-primary ">
              <i class="fas fa-arrow-left"></i> Go Back
            </a>
        <div class="form-header">
            <h2>Add New Document</h2>
            <p>Fill in the details below to add a new document to the system</p>
            
        </div>

        <?php
// Fetch categories
try {
    $query = "SELECT * FROM category_tbl";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . htmlspecialchars($e->getMessage());
    $categories = [];
}

// Fetch sub-categories
try{
    $query = "SELECT * FROM sub_category_tbl";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $sub_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching sub categories: " . htmlspecialchars($e->getMessage());
    $sub_categories = [];
}

// Fetch teachers
try{
    $query = "SELECT * FROM teacher_tbl";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching teachers: " . htmlspecialchars($e->getMessage());
    $teachers = [];
}

    ?>

        <form action="../manage_posts/store_article.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">

                <!-- Article Title -->
                <div class="field-card ">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Article Title
                    </label>
                    <input type="text" name="doc_title" id="doc_title" class="field-input" placeholder="Enter article title" required>
                </div>

                <!-- Author selection -->
                <div class="field-card ">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Select Author
                    </label>
                    <select name="doc_t_id" id="doc_t_id" class="field-select" required>
                        <option value="">-- Select an Author --</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?php echo htmlspecialchars($teacher['t_id']); ?>">
                                <?php echo htmlspecialchars($teacher['t_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date of publish -->
                <div class="field-card ">
                    <label class="field-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date of Publish
                    </label>
                    <input type="date" name="dop" id="dop" class="field-input" required>
                </div>


                <!-- category and sub category -->

                    <div class="field-card ">
                    <label class="field-label">
                        <i class="fas fa-list"></i>
                        Select Category
                    </label>
                    <select name="doc_cat_id" id="doc_cat_id" class="field-select" required>
                        <option value="">-- Select a Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['cat_id']); ?>">
                                <?php echo htmlspecialchars($category['cat_name']); ?>
                            </option>
                            <?php endforeach; ?>
                             <option value="add_new_cat">Add New Category</option>
                    </select>
                </div>

                <div class="field-card " style="display: none;">
                    <label class="field-label">
                        <i class="fas fa-list"></i>
                        Category Name
                    </label>
                    <input type="text" name="cat_name" id="cat_name" class="field-input" placeholder="Enter category name" >
                </div>

                <div class="field-card ">
                    <label class="field-label">
                        <i class="fas fa-list"></i>
                        Select Sub Category
                    </label>
                    <select name="sub_cat_id" id="sub_cat_id" class="field-select" required>
                        <option value="">-- Select a Sub Category --</option>
                        <?php foreach ($sub_categories as $sub_category): ?>
                            <option value="<?php echo htmlspecialchars($sub_category['sub_cat_id']); ?>">
                                <?php echo htmlspecialchars($sub_category['sub_cat_name']); ?>
                            </option>
                            <?php endforeach; ?>
                            <option value="add_new_subcat">Add New Sub Category</option>
                    </select>
                </div>

                  <div class="field-card " style="display: none;">
                    <label class="field-label">
                        <i class="fas fa-list"></i>
                       Sub Category Name
                    </label>
                    <input type="text" name="sub_cat_name" id="sub_cat_name" class="field-input" placeholder="Enter sub category name" >
                </div>

                <!-- About -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        About the Article
                    </label>
                    <textarea name="doc_about" class="field-textarea summernote" placeholder="Describe the article" required></textarea>
                </div>

                <!-- Article Image -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-image"></i>
                        Article Image
                    </label>
                    <div class="file-input-wrapper">
                        <div class="field-file">
                            <label class="file-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload or drag and drop</span>
                            </label>
                            <input type="file" name="doc_img" id="doc_img" accept="image/*" class="file-input">
                        </div>
                        <span class="file-hint">Upload a clear image of the article (JPG, PNG, max 5MB)</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="add_article_page.php">
                <button type="button" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </button></a>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Subject
                </button>
            </div>
        </form>
    </div>
          
    </main>

        <?php
        }
        include('../Include/footer.php');
        ?>
        <script>
      

        // File input display name
        const fileInput = document.querySelector('input[type="file"]');
        const fileLabel = document.querySelector('.file-label span');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileLabel.textContent = this.files[0].name;
            } else {
                fileLabel.textContent = 'Click to upload or drag and drop';
            }
        });
        // Show/hide category name input
        const categorySelect = document.getElementById('doc_cat_id');
        const categoryNameInput = document.getElementById('cat_name');
        categorySelect.addEventListener('change', function() {
            if (this.value === 'add_new_cat') {
                categoryNameInput.parentElement.style.display = 'block';
                categoryNameInput.required = true;
            } else {
                categoryNameInput.parentElement.style.display = 'none';
                categoryNameInput.required = false;
            }
        });
        // Show/hide sub category name input
        const subCategorySelect = document.getElementById('sub_cat_id');
        const subCategoryNameInput = document.getElementById('sub_cat_name');
        subCategorySelect.addEventListener('change', function() {
            if (this.value === 'add_new_subcat') {
                subCategoryNameInput.parentElement.style.display = 'block';
                subCategoryNameInput.required = true;
            } else {
                subCategoryNameInput.parentElement.style.display = 'none';
                subCategoryNameInput.required = false;
            }
        });


    </script>

  <script>
    $(document).ready(function() {
        $(".summernote").summernote({
            height:250
        });
        $('.dropdown-toggle').dropdown();
    });
</script>
