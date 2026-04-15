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
            <h2>Add New Subject</h2>
            <p>Fill in the details below to add a new subject to the system</p>
            
        </div>

        <form action="../edit_and_store_pages/store_subject.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <!-- Subject Name -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Subject Name
                    </label>
                    <input type="text" name="sub_name" id="sub_name" class="field-input" placeholder="Enter full name" required>
                </div>

               
                <!-- About -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        About the Subject
                    </label>
                    <textarea name="sub_about" class="field-textarea" placeholder="Describe the subject" required></textarea>
                </div>

                <!-- Subject Image -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-image"></i>
                        Subject Image
                    </label>
                    <div class="file-input-wrapper">
                        <div class="field-file">
                            <label class="file-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload or drag and drop</span>
                            </label>
                            <input type="file" name="sub_img" id="sub_img" accept="image/*" class="file-input">
                        </div>
                        <span class="file-hint">Upload a clear image of the student (JPG, PNG, max 5MB)</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="add_subject_page.php">
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
    </script>
