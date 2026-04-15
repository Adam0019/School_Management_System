<?php
include('../Include/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
include('../Include/sidebar.php');
$user=$_SESSION['u_name'];
?>
  <!-- ============ MAIN CONTENT SECTION ============ -->
    <main class="main-content">
        <div class="form-container">
             <a href="../pages/students_page.php">
              <button type="button" class="btn btn-primary "> 
               Go Back <--
            </button></a>
        <div class="form-header">
            <h2>Edit Student Details</h2>
            <p>Fill in the details below to edit and update the student info to the system</p>
        </div>
<?php
try{
    $s_id = $_GET['s_id'];
    $SELECT = "SELECT * FROM student_tbl WHERE s_id=?";
    $stmt = $pdo->prepare($SELECT);
    $stmt->execute([$s_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        echo "Error fetching data: ".$e->getMessage();
    }?>

    
        <form id="studentForm" action="../edit_and_store_pages/edit_student_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="s_id" value="<?php echo $row['s_id']; ?>">
            <div class="form-grid">
                <!-- Student Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Student Name
                    </label>
                    <input type="text" name="s_name" id="s_name" class="field-input" placeholder="Enter full name" value="<?php echo $row['s_name']; ?>" required>
                </div>
                <!-- Gender -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-venus-mars"></i>
                        </label>
                    <select name="s_gender" id="s_gender"  class="field-select" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male" <?php if($row['s_gender']=="Male") echo "selected";?>>Male</option>
                        <option value="Female" <?php if($row['s_gender']=="Female") echo "selected";?>>Female</option>
                        <option value="Other" <?php if($row['s_gender']=="Other") echo "selected";?>>Other</option>
                        </select>
                </div>
                <!-- Date of Birth -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date of Birth
                    </label>
                    <input type="date" name="s_dob" id="s_id" class="field-input" value="<?php echo $row['s_dob']; ?>" required>

            </div>
            <!-- Guardian Name -->

            <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-friends"></i>
                        Guardian Name
                    </label>
                    <input type="text" name="s_g_name" id="s_g_name" class="field-input" placeholder="Enter guardian name" value="<?php echo $row['s_g_name']; ?>" required>
                </div>

                <!-- Guardian Relation -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-shield"></i>
                        Guardian Relation
                    </label>
                    <select name="s_g_type" id="s_g_type"  class="field-select" required>
                        <option value="" disabled selected>Select Guardian Relation</option>
                        <option value="Father" <?php if($row['s_g_type']=="Father") echo "selected";?>>Father</option>
                        <option value="Mother" <?php if($row['s_g_type']=="Mother") echo "selected";?>>Mother</option>
                        <option value="Other" <?php if($row['s_g_type']=="Other") echo "selected";?>>Other</option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-phone"></i>
                        Phone Number
                    </label>
                    <input type="text" name="s_phone" id="s_phone" class="field-input" placeholder="Enter phone number" value="<?php echo $row['s_phone']; ?>" required>
                </div>

                <!-- Address -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Address
                    </label>
                    <input type="text" name="s_address" id="s_address" class="field-textarea" placeholder="Enter address" value="<?php echo $row['s_address']; ?>">
                </div>
                <!-- Class -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-school"></i>
                        Class
                    </label>
                    <select name="s_class" id="s_class" class="field-select" required>
                        <option value="" disabled selected>Select Class</option>
                        <option value="Five" <?php if($row['s_class']=="Five") echo "selected";?>>Five</option>
                        <option value="Six" <?php if($row['s_class']=="Six") echo "selected";?>>Six</option>
                        <option value="Seven" <?php if($row['s_class']=="Seven") echo "selected";?>>Seven</option>
                        <option value="Eight" <?php if($row['s_class']=="Eight") echo "selected";?>>Eight</option>
                        <option value="Nine" <?php if($row['s_class']=="Nine") echo "selected";?>>Nine</option>
                        <option value="Ten" <?php if($row['s_class']=="Ten") echo "selected";?>>Ten</option>
                        <option value="Eleven" <?php if($row['s_class']=="Eleven") echo "selected";?>>Eleven</option>
                        <option value="Twelve" <?php if($row['s_class']=="Twelve") echo "selected";?>>Twelve</option>
                    </select>
                    </div>
                <!-- Roll Number -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-id-badge"></i>
                        Roll Number
                    </label>
                    <input type="text" name="s_roll" id="s_roll" class="field-input" placeholder="Enter roll number" value="<?php echo $row['s_roll']; ?>" required>
                    </div>
                    <!-- section -->
                    <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-chalkboard"></i>
                        Section
                    </label>
                    <select name="s_section" id="s_section" class="field-select">
                        <option value="" disabled selected>Select Section</option>
                        <option value="A" <?php if($row['s_section']=="A") echo "selected";?>>A</option>
                        <option value="B" <?php if($row['s_section']=="B") echo "selected";?>>B</option>
                        <option value="C" <?php if($row['s_section']=="C") echo "selected";?>>C</option>
                        <option value="D" <?php if($row['s_section']=="D") echo "selected";?>>D</option>
                    </select>
                    </div>
                    </div>
                    <div class="form-actions">
                        <a href="edit_student_profile.php?s_id=<?php echo $row['s_id']; ?>">
                        <button type="button" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button></a>
                        <button type="submit" name="submit" value="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Student
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