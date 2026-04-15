<?php
include('../Include/header.php');
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}

$class = $_SESSION['class'];
include('../Include/sidebar.php');

// BUG FIX #1 — s_id was never validated; missing or non-numeric value would
// query the wrong row or throw an error.
if (!isset($_GET['s_id']) || !is_numeric($_GET['s_id'])) {
    header('Location: ../pages/students_page.php');
    exit;
}

$row = [];
try {
    $s_id   = intval($_GET['s_id']);
    $SELECT = "SELECT * FROM student_tbl WHERE s_id = ?";
    $stmt   = $pdo->prepare($SELECT);
    $stmt->execute([$s_id]);
    $row    = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        header('Location: ../pages/students_page.php');
        exit;
    }
} catch(PDOException $e) {
    error_log("Error fetching student: " . $e->getMessage());
    header('Location: ../pages/students_page.php');
    exit;
}

// Fetch classes for dropdown
try {
    $cStmt = $pdo->prepare("SELECT * FROM class_tbl ORDER BY c_name ASC");
    $cStmt->execute();
    $classes = $cStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $classes = [];
}

// Build class → sections map for dynamic section filtering (same as add_student_page.php)
$classSectionsMap = [];
foreach($classes as $class) {
    $sections = [];
    foreach(['section_a','section_b','section_c','section_d'] as $col) {
        if(!empty($class[$col])) $sections[] = $class[$col];
    }
    $classSectionsMap[$class['c_id']] = $sections;
}
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content">
    <div class="form-container">
        <a href="../pages/students_page.php?class=<?php echo $class; ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>

        <div class="form-header">
            <h2>Edit Student Details</h2>
            <p>Fill in the details below to edit and update the student info</p>
        </div>

        <!-- Profile Picture -->
        <div class="profile-unified-card" style="margin-bottom: 4rem;">
            <div class="profile-image-container" style="margin-top: 10px; position: relative;">
                <?php if (!empty($row['s_img'])): ?>
                    <img src="../assets/image/<?php echo htmlspecialchars($row['s_img'], ENT_QUOTES, 'UTF-8'); ?>"
                         alt="Student Image" class="profile-img" id="profileImage">
                <?php elseif ($row['s_gender'] === 'Male'): ?>
                    <img src="../assets/image/male_default.jpeg" alt="Student Image" class="profile-img" id="profileImage">
                <?php elseif ($row['s_gender'] === 'Female'): ?>
                    <img src="../assets/image/female_default.jpeg" alt="Student Image" class="profile-img" id="profileImage">
                <?php else: ?>
                    <img src="../assets/image/default.jpg" alt="Student Image" class="profile-img" id="profileImage">
                <?php endif; ?>

                <label for="imageUpload" class="profile-edit-btn">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="imageUpload" name="s_img" accept="image/*"
                       style="display: none;" form="studentForm">
            </div>
        </div>

        <form id="studentForm" action="../edit_and_store_pages/update_student_profile.php"
              method="POST" enctype="multipart/form-data">
            <input type="hidden" name="s_id" value="<?php echo intval($row['s_id']); ?>">

            <div class="form-grid">

                <!-- Student Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Student Name
                    </label>
                    <input type="text" name="s_name" id="s_name" class="field-input"
                           placeholder="Enter full name"
                           value="<?php echo htmlspecialchars($row['s_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Gender -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-venus-mars"></i>
                        Gender
                    </label>
                    <select name="s_gender" id="s_gender" class="field-select" required>
                        <option value="" disabled>Select Gender</option>
                        <option value="Male"   <?php if($row['s_gender']==="Male")   echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if($row['s_gender']==="Female") echo "selected"; ?>>Female</option>
                        <option value="Other"  <?php if($row['s_gender']==="Other")  echo "selected"; ?>>Other</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date of Birth
                    </label>
                    <!-- BUG FIX #2 — id was "s_id", clashing with the hidden s_id input.
                         Changed to "s_dob". -->
                    <input type="date" name="s_dob" id="s_dob" class="field-input"
                           max="<?php echo date('Y-m-d'); ?>"
                           value="<?php echo htmlspecialchars($row['s_dob'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Guardian Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-friends"></i>
                        Guardian Name
                    </label>
                    <input type="text" name="s_g_name" id="s_g_name" class="field-input"
                           placeholder="Enter guardian name"
                           value="<?php echo htmlspecialchars($row['s_g_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Guardian Relation -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-shield"></i>
                        Guardian Relation
                    </label>
                    <select name="s_g_type" id="s_g_type" class="field-select" required>
                        <option value="" disabled>Select Guardian Relation</option>
                        <option value="Father" <?php if($row['s_g_type']==="Father") echo "selected"; ?>>Father</option>
                        <option value="Mother" <?php if($row['s_g_type']==="Mother") echo "selected"; ?>>Mother</option>
                        <option value="Other"  <?php if($row['s_g_type']==="Other")  echo "selected"; ?>>Other</option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-phone"></i>
                        Phone Number
                    </label>
                    <input type="tel" name="s_phone" id="s_phone" class="field-input"
                           placeholder="Enter 10-digit phone number"
                           pattern="[0-9]{10}" maxlength="10"
                           value="<?php echo htmlspecialchars($row['s_phone'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Class -->
                <!-- BUG FIX #3 — class dropdown was matching and storing c_name (string)
                     but student_tbl.s_class stores c_id (numeric). Changed value to c_id
                     and match comparison to c_id, consistent with add_student_page.php
                     and store_student.php. -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-school"></i>
                        Class
                    </label>
                    <select name="s_class" id="s_class" class="field-select" required>
                        <option value="" disabled>Select Class</option>
                        <?php foreach($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class['c_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php if($row['s_class'] == $class['c_id']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($class['c_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Roll Number -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-id-badge"></i>
                        Roll Number
                    </label>
                    <input type="text" name="s_roll" id="s_roll" class="field-input"
                           placeholder="Enter roll number"
                           value="<?php echo htmlspecialchars($row['s_roll'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Section — dynamically populated based on selected class -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-chalkboard"></i>
                        Section
                    </label>
                    <select name="s_section" id="s_section" class="field-select">
                        <option value="">Select Section</option>
                        <?php
                        // Pre-populate sections for the currently saved class
                        $currentClassId = $row['s_class'];
                        if (!empty($classSectionsMap[$currentClassId])) {
                            foreach($classSectionsMap[$currentClassId] as $sec) {
                                $selected = ($row['s_section'] === $sec) ? "selected" : "";
                                echo "<option value='" . htmlspecialchars($sec, ENT_QUOTES, 'UTF-8') . "' $selected>"
                                   . htmlspecialchars($sec, ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <small id="section-hint" style="color:#888; font-size:0.8em;">
                        Change class to reload sections.
                    </small>
                </div>

                <!-- Address -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Address
                    </label>
                    <textarea name="s_address" id="s_address" class="field-textarea"
                              placeholder="Enter address"><?php echo htmlspecialchars($row['s_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

            </div><!-- /.form-grid -->

            <div class="form-actions">
                <!-- BUG FIX #4 — <a><button> invalid HTML nesting; changed to plain <a> -->
                <a href="edit_student_profile.php?s_id=<?php echo intval($row['s_id']); ?>"
                   class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Student
                </button>
            </div>
        </form>
    </div>
</main>

<?php include('../Include/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const classSectionsMap = <?php echo json_encode($classSectionsMap); ?>;

    // ── 1. Class → Section dynamic filtering ──────────────────────────
    const classSelect   = document.getElementById('s_class');
    const sectionSelect = document.getElementById('s_section');
    const sectionHint   = document.getElementById('section-hint');

    classSelect.addEventListener('change', function () {
        const classId  = this.value;
        const sections = classSectionsMap[classId] || [];

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
            sections.forEach(sec => sectionSelect.add(new Option(sec, sec)));
            sectionSelect.disabled  = false;
            sectionHint.textContent = sections.length + ' section(s) available.';
        }
    });

    // ── 2. Phone — digits only ─────────────────────────────────────────
    const phoneInput = document.getElementById('s_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    }

    // ── 3. Profile image live preview ─────────────────────────────────
    const imageUpload = document.getElementById('imageUpload');
    const profileImage = document.getElementById('profileImage');

    if (imageUpload) {
        imageUpload.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size must not exceed 5 MB.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => { profileImage.src = e.target.result; };
                reader.readAsDataURL(file);
            }
        });
    }

});
</script>