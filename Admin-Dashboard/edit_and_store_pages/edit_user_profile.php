<?php
include('../Include/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
include('../Include/sidebar.php');
$user=$_SESSION['u_name'];
$u_id=$_SESSION['u_id'];
?>
  <!-- ============ MAIN CONTENT SECTION ============ -->
    <main class="main-content">
        <div class="form-container">
        <div class="form-header">
            <h2>Edit Profile</h2>
        </div>
    <?php
  try{
    $SELECT="SELECT * FROM user_tbl WHERE u_id=?";
    $stmt=$pdo->prepare($SELECT);
    $stmt->execute([$u_id]);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    echo "Error fetching data: ".$e->getMessage();
    $row = []; // Initialize empty array on error
}?>
<!-- Single Unified Profile Card -->
    <div class="profile-unified-card"style="margin-bottom: 4rem;">
        <!-- Card Header with Gradient Background -->
        <!-- <div class="profile-card-header"></div> -->
        
        <!-- Profile Image -->
        <div class="profile-image-container"style="margin-top: 10px; position: relative;">
            <?php
            if($row['u_img']!="" && $row['u_img']!=NULL){
            ?>
                <img src="../assets/image/<?php echo $row['u_img']; ?>" alt="User Image" class="profile-img" id="profileImage">
            <?php
            } else {
                if($row['u_gender']=="Male"){
            ?>
                    <img src="./assets/image/male_default.jpeg" alt="User Image" class="profile-img" id="profileImage">
            <?php
                } else if($row['u_gender']=="Female"){
            ?>
                    <img src="../assets/image/female_default.jpeg" alt="User Image" class="profile-img" id="profileImage">
            <?php
                } else {
            ?>
                    <img src="../assets/image/default.jpg" alt="User Image" class="profile-img" id="profileImage">
            <?php
                }
            }
            ?>
                  <!-- Edit Button Overlay -->
                <label for="imageUpload" class="profile-edit-btn">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="imageUpload" name="u_img" accept="image/*" style="display: none;" form="userForm">
            </div> 
        </div>
    
        <form id="userForm" action="../edit_and_store_pages/update_user_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="u_id" value="<?php echo $row['u_id']; ?>">
            <div class="form-grid">
                <!-- User Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        User Name
                    </label>
                    <input type="text" name="u_name" id="u_name" class="field-input" placeholder="Enter full name" value="<?php echo $row['u_name']; ?>" required>
                </div>
                <!-- Gender -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-venus-mars"></i>
                        </label>
                    <select name="u_gender" id="u_gender"  class="field-select" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male" <?php if($row['u_gender']=="Male") echo "selected";?>>Male</option>
                        <option value="Female" <?php if($row['u_gender']=="Female") echo "selected";?>>Female</option>
                        <option value="Other" <?php if($row['u_gender']=="Other") echo "selected";?>>Other</option>
                        </select>
                </div>
             
            <!-- Email -->

            <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-friends"></i>
                        Email
                    </label>
                    <input type="text" name="u_email" id="u_email" class="field-input" placeholder="Enter email" value="<?php echo $row['u_email']; ?>" required>
                </div>

                <!-- Phone Number -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-phone"></i>
                        Phone Number
                    </label>
                    <input type="text" name="u_phone" id="u_phone" class="field-input" placeholder="Enter phone number" value="<?php echo $row['u_phone']; ?>" required>
                </div>

                <!-- Address -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Address
                    </label>
                    <textarea name="u_address" id="u_address" class="field-textarea" placeholder="Enter address" ><?php echo $row['u_address']; ?> </textarea>
                </div>
              <!-- About Me -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-info-circle"></i>
                        About Me
                    </label>
                    <textarea name="u_about" id="u_about" class="field-textarea" placeholder="Write something about yourself..." rows="4"><?php echo $row['u_about']; ?></textarea>
                    </div>
                </div>
                <div class="form-actions">
                        <a href="edit_user_profile.php?u_id=<?php echo $row['u_id']; ?>">
                        <button type="button" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button></a>
                        <button type="submit" name="submit" value="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update User
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

        // File input display name

        // const fileInput = document.querySelector('input[type="file"]');
        // const fileLabel = document.querySelector('.file-label span');

        // fileInput.addEventListener('change', function() {
        //     if (this.files.length > 0) {
        //         fileLabel.textContent = this.files[0].name;
        //     } else {
        //         fileLabel.textContent = 'Click to upload or drag and drop';
        //     }
        // });


    </script>