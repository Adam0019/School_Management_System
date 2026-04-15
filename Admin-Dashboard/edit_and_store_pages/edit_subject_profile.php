<?php
include('../Include/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
include('../Include/sidebar.php');
$user=$_SESSION['u_name'];
// Generate CSRF token if not already set
// if (empty($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// }
?>
  <!-- ============ MAIN CONTENT SECTION ============ -->
    <main class="main-content">
        <div class="form-container">
             <a href="../pages/subjects_page.php" class="btn btn-primary ">
             <!-- <a href="../pages/test.php?s_id=1"> -->
              <i class="fas fa-arrow-left"></i> Go Back 
            </a>
        <div class="form-header">
            <h2>Edit Subject Details</h2>
            <p>Fill in the details below to edit and update the subject info to the system</p>
        </div>
<?php
try {
    // Get subject ID from URL
   $sub_id = filter_input(INPUT_GET, 'sub_id', FILTER_VALIDATE_INT);
if (!$sub_id || $sub_id <= 0) {
    echo "<p class='error'>Invalid subject ID.</p>";
    exit;
}
    
    if ($sub_id !== null) {
        // Fetch subject details from database
        $SELECT = "SELECT * FROM subject_tbl WHERE sub_id = :sub_id";
        $stmt = $pdo->prepare($SELECT);
        $stmt->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $subject = $stmt->fetch(PDO::FETCH_ASSOC);
            // Subject details are now in the $subject array
            // You can use this data to pre-fill the form fields
        } else {
            echo "<p class='error'>Subject not found.</p>";
            exit;
        }
    } else {
        echo "<p class='error'>No subject ID provided.</p>";
        exit;
    }
 } catch (PDOException $e) {
    error_log($e->getMessage()); // log privately
    echo "<p class='error'>An error occurred. Please try again.</p>";
    exit;

}
?>
  <!-- Profile Picture Display Section -->
        <div class="profile-unified-card" style="margin-bottom: 4rem;">
<!-- Subject Image -->
 <div class="profile-image-container" style="margin-top: 10px; position: relative;">
    <?php
    
    if (!empty($subject['sub_img'])) {
    ?>
        <img src="../assets/image/<?php echo $subject['sub_img']; ?>" alt="Subject Image" class="profile-img" onerror="this.src='../assets/image/subject_default.jpg'" id="profileImage">
    <?php
    } else {
        // Default image for subjects
    ?>
        <img src="../assets/image/subject_default.jpg" alt="Subject Image" class="profile-img" id="profileImage">
    <?php
    }
    ?>
    <!-- Edit Button Overlay -->
                <label for="imageUpload" class="profile-edit-btn">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="imageUpload" name="sub_img" accept="image/*" style="display: none;" form="subjectForm">
            </div>
 </div>

        <form id="subjectForm" action="../edit_and_store_pages/update_subject_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="sub_id" value="<?php echo intval($subject['sub_id'] ?? 0); ?>">
             <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <div class="form-grid">
                <!-- Subject Name -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Subject Name
                    </label>
                    <input type="text" name="sub_name" id="sub_name" class="field-input" placeholder="Enter full name" value="<?php echo htmlspecialchars($subject['sub_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

               
                <!-- About -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        About
                    </label>
                    <textarea name="sub_about" class="field-textarea" placeholder="Describe the subject" required><?php echo htmlspecialchars($subject['sub_about'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
            </div>
            <div class="form-actions">
                <a href="edit_subject_page.php?sub_id=<?php echo intval($subject['sub_id'] ?? 0); ?>">
                <button type="button" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </button></a>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Subject
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
        <script>
// Profile image upload and preview
        const imageUpload = document.getElementById('imageUpload');
        const profileImage = document.getElementById('profileImage');

        imageUpload.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        </script>