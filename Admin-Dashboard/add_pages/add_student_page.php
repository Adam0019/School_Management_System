<?php
include('../Include/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
include('../Include/sidebar.php');
$user=$_SESSION['u_name'];
?>
  <!-- ============ MAIN CONTENT SECTION ============ -->
   <?php
   // Fetch classes for dropdown
   try {
    $query = "SELECT * FROM class_tbl";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error fetching classes: " . $e->getMessage();
    $classes = [];
}

// Build a map of class_id => [sections] for use in JS
$classSectionsMap = [];
foreach($classes as $class) {
    $sections = [];
    foreach(['section_a','section_b','section_c','section_d'] as $col) {
        if(!empty($class[$col])) {
            $sections[] = $class[$col];
        }
    }
    $classSectionsMap[$class['c_id']] = $sections;
}
   ?>
    <main class="main-content">
        <div class="form-container">
             <a href="../pages/students_page.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
        <div class="form-header">
            <h2>Add New Student</h2>
            <p>Fill in the details below to add a new student to the system</p>
        </div>

        <form action="../edit_and_store_pages/store_student.php" method="POST" enctype="multipart/form-data" id="addStudentForm">
            <div class="form-grid">
                <!-- Student Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Student Name
                    </label>
                    <input type="text" name="s_name" id="s_name" class="field-input" placeholder="Enter full name" required>
                </div>

                <!-- Gender -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-venus-mars"></i>
                        Gender
                    </label>
                    <select name="s_gender" id="s_gender" class="field-select" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date of Birth
                    </label>
                    <input type="date" name="s_dob" id="s_dob" class="field-input"
                           max="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <!-- Guardian Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-friends"></i>
                        Guardian Name
                    </label>
                    <input type="text" name="s_g_name" class="field-input" placeholder="Enter guardian name" required>
                </div>

                <!-- Guardian Relation -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-tag"></i>
                        Guardian Relation
                    </label>
                    <select name="s_g_type" id="s_g_type" class="field-select" required>
                        <option value="" disabled selected>Select Relation</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Phone -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-phone"></i>
                        Phone Number
                    </label>
                    <input type="tel" name="s_phone" id="s_phone" class="field-input"
                           placeholder="Enter 10-digit phone number"
                           pattern="[0-9]{10}" maxlength="10"
                           title="Please enter a valid 10-digit phone number" required>
                </div>

                <!-- Class -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-school"></i>
                        Class
                    </label>
                    <select name="s_class" class="field-select" id="s_class" required>
                        <option value="" disabled selected>Select Class</option>
                        <?php foreach($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class['c_id']); ?>">
                                <?php echo htmlspecialchars($class['c_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Roll no. -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-id-badge"></i>
                        Roll No.
                    </label>
                    <input type="text" name="s_roll" id="s_roll" class="field-input" placeholder="Enter roll no." required>
                </div>

                <!-- Section — dynamically populated based on selected class -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-chalkboard"></i>
                        Section
                    </label>
                    <select name="s_section" class="field-select" id="s_section" required disabled>
                        <option value="" disabled selected>Select Class First</option>
                    </select>
                    <small class="field-hint" id="section-hint" style="color:#888; font-size:0.8em;">
                        Please select a class to load sections.
                    </small>
                </div>

                <!-- Address -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Address
                    </label>
                    <textarea name="s_address" class="field-textarea" placeholder="Enter complete address" required></textarea>
                </div>

                <!-- Student Image -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-image"></i>
                        Student Image
                    </label>
                    <div class="file-input-wrapper">
                        <div class="field-file">
                            <label class="file-label" for="s_img">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span id="file-label-text">Click to upload or drag and drop</span>
                            </label>
                            <input type="file" name="s_img" id="s_img" accept="image/*" class="file-input">
                        </div>
                        <span class="file-hint">Upload a clear image of the student (JPG, PNG, max 5MB)</span>
                        <!-- Image preview -->
                        <div id="image-preview-wrapper" style="display:none; margin-top:10px;">
                            <img id="image-preview" src="#" alt="Preview"
                                 style="max-width:120px; max-height:120px; border-radius:8px; border:1px solid #ddd;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="add_student_page.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Student
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
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. Class → Section dynamic filtering ──────────────────────────
    const classSectionsMap = <?php echo json_encode($classSectionsMap); ?>;

    const classSelect   = document.getElementById('s_class');
    const sectionSelect = document.getElementById('s_section');
    const sectionHint   = document.getElementById('section-hint');

    classSelect.addEventListener('change', function () {
        const classId  = this.value;
        const sections = classSectionsMap[classId] || [];

        // Reset section dropdown
        sectionSelect.innerHTML = '';
        sectionSelect.disabled  = true;

        if (sections.length === 0) {
            const opt = new Option('No sections available', '');
            opt.disabled = opt.selected = true;
            sectionSelect.add(opt);
            sectionHint.textContent = 'No sections found for this class.';
        } else {
            const placeholder = new Option('Select Section', '');
            placeholder.disabled = placeholder.selected = true;
            sectionSelect.add(placeholder);

            sections.forEach(function (sec) {
                sectionSelect.add(new Option(sec, sec));
            });

            sectionSelect.disabled  = false;
            sectionHint.textContent = sections.length + ' section(s) available.';
        }
    });

    // ── 2. Phone – digits only ─────────────────────────────────────────
    const phoneInput = document.getElementById('s_phone');
    phoneInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });

    // ── 3. File input: show file name + image preview ──────────────────
    const fileInput    = document.getElementById('s_img');
    const fileLabelTxt = document.getElementById('file-label-text');
    const previewWrap  = document.getElementById('image-preview-wrapper');
    const previewImg   = document.getElementById('image-preview');

    fileInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];

            // File size guard (5 MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Image size must not exceed 5 MB.');
                this.value = '';
                fileLabelTxt.textContent = 'Click to upload or drag and drop';
                previewWrap.style.display = 'none';
                return;
            }

            fileLabelTxt.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src         = e.target.result;
                previewWrap.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            fileLabelTxt.textContent  = 'Click to upload or drag and drop';
            previewWrap.style.display = 'none';
        }
    });

});
</script>