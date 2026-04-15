<?php
include('../Include/header.php');
if(empty($_SESSION['userAuth'])) {
    header("Location: ../login.php");
    exit();
}
include('../Include/sidebar.php');
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

// Fetch subjects for dropdown
try {
    $query1 = "SELECT * FROM subject_tbl";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute();
    $subjects = $stmt1->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error fetching subjects: " . $e->getMessage();
    $subjects = [];
}
?>

<main class="main-content">
    <div class="form-container">
        <a href="../pages/teachers_page.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>

        <div class="form-header">
            <h2>Add New Teacher</h2>
            <p>Fill in the details below to add a new teacher to the system</p>
        </div>

        <form action="../edit_and_store_pages/store_teacher.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">

                <!-- Teacher Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Teacher Name
                    </label>
                    <input type="text" name="t_name" class="field-input" placeholder="Enter full name" required>
                </div>

                <!-- Gender -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-venus-mars"></i>
                        Gender
                    </label>
                    <select name="t_gender" class="field-select" required>
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
                    <input type="date" name="t_dob" class="field-input"
                           max="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <!-- Phone -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-phone"></i>
                        Phone Number
                    </label>
                    <input type="tel" name="t_phone" id="t_phone" class="field-input"
                           placeholder="Enter 10-digit phone number"
                           pattern="[0-9]{10}" maxlength="10"
                           title="Please enter a valid 10-digit phone number" required>
                </div>

                <!-- Email -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" name="t_email" class="field-input" placeholder="Enter email address" required>
                </div>

                <!-- Role -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-tag"></i>
                        Teacher Role
                    </label>
                    <select name="t_role" id="t_role" class="field-select" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="Class Teacher">Class Teacher</option>
                        <option value="Para Teacher">Para Teacher</option>
                        <option value="Subject Teacher">Subject Teacher</option>
                        <option value="Games Teacher">Games Teacher</option>
                    </select>
                </div>

                <!-- Class Teacher Of (shown only when role = Class Teacher) -->
                <div class="field-card" id="teacher_type" style="display: none;">
                    <label class="field-label">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Class Teacher Of
                    </label>
                    <select name="t_class" id="t_class" class="field-select">
                        <option value="" selected>None</option>
                        <?php foreach($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class['c_id']); ?>">
                                <?php echo htmlspecialchars($class['c_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Subject Main -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-school"></i>
                        Subject Main
                    </label>
                    <!-- BUG FIX #1 — was "required" but a teacher may not have a main subject
                         (e.g. Games Teacher). Changed to optional with a "None" default. -->
                    <select name="t_subject_main" class="field-select" id="t_subject_main">
                        <option value="" selected>None / Not Assigned</option>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject['sub_id']); ?>">
                                <?php echo htmlspecialchars($subject['sub_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Subject Secondary 1 -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-school"></i>
                        Subject Secondary 1
                    </label>
                    <!-- BUG FIX #1 (continued) — same issue, secondary subjects are optional -->
                    <select name="t_subject_sec_1" class="field-select" id="t_subject_sec_1">
                        <option value="" selected>None / Not Assigned</option>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject['sub_id']); ?>">
                                <?php echo htmlspecialchars($subject['sub_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Subject Secondary 2 -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-school"></i>
                        Subject Secondary 2
                    </label>
                    <!-- BUG FIX #1 (continued) — same issue -->
                    <select name="t_subject_sec_2" class="field-select" id="t_subject_sec_2">
                        <option value="" selected>None / Not Assigned</option>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject['sub_id']); ?>">
                                <?php echo htmlspecialchars($subject['sub_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Address -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Address
                    </label>
                    <textarea name="t_address" class="field-textarea" placeholder="Enter complete address"></textarea>
                </div>

                <!-- About -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-info-circle"></i>
                        About Teacher
                    </label>
                    <textarea name="t_about" class="field-textarea" placeholder="Brief description about the teacher"></textarea>
                </div>

                <!-- Teacher Image -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-image"></i>
                        Teacher Image
                    </label>
                    <div class="file-input-wrapper">
                        <div class="field-file">
                            <!-- BUG FIX #2 — file label had no `for` attribute linking it to the
                                 input, so clicking the label did not open the file picker. Added
                                 for="t_img" to match the input's id. -->
                            <label class="file-label" for="t_img">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span id="file-label-text">Click to upload or drag and drop</span>
                            </label>
                            <input type="file" name="t_img" id="t_img" accept="image/*" class="file-input">
                        </div>
                        <span class="file-hint">Upload a clear image of the teacher (JPG, PNG, max 5MB)</span>
                        <!-- Image preview (consistent with add_student_page.php) -->
                        <div id="image-preview-wrapper" style="display:none; margin-top:10px;">
                            <img id="image-preview" src="#" alt="Preview"
                                 style="max-width:120px; max-height:120px; border-radius:8px; border:1px solid #ddd;">
                        </div>
                    </div>
                </div>

            </div><!-- /.form-grid -->

            <div class="form-actions">
                <!-- BUG FIX #3 — Cancel was wrapped as <a><button>, which is invalid HTML.
                     A <button> inside an <a> tag is not permitted. Changed to a plain <a> styled
                     as a button, consistent with how cancel works on add_student_page.php. -->
                <a href="add_teacher_page.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" name="add_teacher" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Teacher
                </button>
            </div>
        </form>
    </div>
</main>

<?php include('../Include/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. Show/hide "Class Teacher Of" based on role ──────────────────
    const tRoleSelect   = document.getElementById('t_role');
    const teacherTypeDiv = document.getElementById('teacher_type');
    const tClassSelect  = document.getElementById('t_class');

    if (tRoleSelect && teacherTypeDiv) {
        tRoleSelect.addEventListener('change', function () {
            if (this.value === 'Class Teacher') {
                teacherTypeDiv.style.display = 'block';
            } else {
                teacherTypeDiv.style.display = 'none';
                tClassSelect.value = ''; // clear selection when hidden
            }
        });
    }

    // ── 2. Phone — digits only ─────────────────────────────────────────
    const phoneInput = document.getElementById('t_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    }

    // ── 3. File input: show name + image preview ───────────────────────
    const fileInput   = document.getElementById('t_img');
    const fileLabelTxt = document.getElementById('file-label-text');
    const previewWrap = document.getElementById('image-preview-wrapper');
    const previewImg  = document.getElementById('image-preview');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];

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
                    previewImg.src = e.target.result;
                    previewWrap.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                fileLabelTxt.textContent = 'Click to upload or drag and drop';
                previewWrap.style.display = 'none';
            }
        });
    }

});
</script>