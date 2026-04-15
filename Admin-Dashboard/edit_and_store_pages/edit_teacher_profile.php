<?php
include('../Include/header.php');
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}
include('../Include/sidebar.php');

if (!isset($_GET['t_id']) || !is_numeric($_GET['t_id'])) {
    header('Location: ../pages/teachers_page.php');
    exit;
}

$row = [];
try {
    $t_id   = intval($_GET['t_id']);
    $SELECT = "SELECT * FROM teacher_tbl WHERE t_id = ?";
    $stmt   = $pdo->prepare($SELECT);
    $stmt->execute([$t_id]);
    $row    = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        header('Location: ../pages/teachers_page.php');
        exit;
    }
} catch(PDOException $e) {
    error_log("Error fetching teacher: " . $e->getMessage());
    header('Location: ../pages/teachers_page.php');
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

// Fetch subjects for dropdown
try {
    $sStmt = $pdo->prepare("SELECT * FROM subject_tbl ORDER BY sub_name ASC");
    $sStmt->execute();
    $subjects = $sStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $subjects = [];
}
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content">
    <div class="form-container">
        <a href="../pages/teachers_page.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>

        <div class="form-header">
            <h2>Edit Teacher Details</h2>
            <p>Fill in the details below to edit and update the teacher info</p>
        </div>

        <!-- Profile Picture Display -->
        <div class="profile-unified-card" style="margin-bottom: 4rem;">
            <div class="profile-image-container" style="margin-top: 10px; position: relative;">
                <?php
                if (!empty($row['t_img'])) {
                ?>
                    <img src="../assets/image/<?php echo htmlspecialchars($row['t_img'], ENT_QUOTES, 'UTF-8'); ?>"
                         alt="Teacher Image" class="profile-img" id="profileImage">
                <?php } elseif ($row['t_gender'] === 'Male') { ?>
                    <img src="../assets/image/male_default.jpeg" alt="Teacher Image" class="profile-img" id="profileImage">
                <?php } elseif ($row['t_gender'] === 'Female') { ?>
                    <img src="../assets/image/female_default.jpeg" alt="Teacher Image" class="profile-img" id="profileImage">
                <?php } else { ?>
                    <img src="../assets/image/default.jpg" alt="Teacher Image" class="profile-img" id="profileImage">
                <?php } ?>

                <label for="imageUpload" class="profile-edit-btn">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="imageUpload" name="t_img" accept="image/*"
                       style="display: none;" form="teacherForm">
            </div>
        </div>

        <form id="teacherForm" action="../edit_and_store_pages/update_teacher_profile.php"
              method="POST" enctype="multipart/form-data">
            <input type="hidden" name="t_id" value="<?php echo intval($row['t_id']); ?>">

            <div class="form-grid">

                <!-- Teacher Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Teacher Name
                    </label>
                    <input type="text" name="t_name" id="t_name" class="field-input"
                           placeholder="Enter full name"
                           value="<?php echo htmlspecialchars($row['t_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Gender -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-venus-mars"></i>
                        Gender
                    </label>
                    <select name="t_gender" id="t_gender" class="field-select" required>
                        <option value="" disabled>Select Gender</option>
                        <option value="Male"   <?php if($row['t_gender']==="Male")   echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if($row['t_gender']==="Female") echo "selected"; ?>>Female</option>
                        <option value="Other"  <?php if($row['t_gender']==="Other")  echo "selected"; ?>>Other</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date of Birth
                    </label>
                    <input type="date" name="t_dob" id="t_dob" class="field-input"
                           max="<?php echo date('Y-m-d'); ?>"
                           value="<?php echo htmlspecialchars($row['t_dob'], ENT_QUOTES, 'UTF-8'); ?>" required>
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
                           value="<?php echo htmlspecialchars($row['t_phone'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Email -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" name="t_email" id="t_email" class="field-input"
                           placeholder="Enter email address"
                           value="<?php echo htmlspecialchars($row['t_email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <!-- Role -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-tag"></i>
                        Teacher Role
                    </label>
                    <select name="t_role" id="t_role" class="field-select" required>
                        <option value="" disabled>Select Role</option>
                        <option value="Class Teacher"   <?php if($row['t_role']==="Class Teacher")   echo "selected"; ?>>Class Teacher</option>
                        <option value="Para Teacher"    <?php if($row['t_role']==="Para Teacher")    echo "selected"; ?>>Para Teacher</option>
                        <option value="Subject Teacher" <?php if($row['t_role']==="Subject Teacher") echo "selected"; ?>>Subject Teacher</option>
                        <option value="Games Teacher"   <?php if($row['t_role']==="Games Teacher")   echo "selected"; ?>>Games Teacher</option>
                    </select>
                </div>

                <!-- Class Teacher Of (conditional) -->
                <div class="field-card" id="teacher_type" style="display: none;">
                    <label class="field-label">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Class Teacher Of
                    </label>
                    <select name="t_class" id="t_class" class="field-select">
                        <option value="">None</option>
                        <?php foreach($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class['c_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php if($row['t_class'] == $class['c_id']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($class['c_name'], ENT_QUOTES, 'UTF-8'); ?>
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
                    <select name="t_subject_main" class="field-select" id="t_subject_main">
                        <option value="">None / Not Assigned</option>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject['sub_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php if($row['t_subject_main'] == $subject['sub_id']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($subject['sub_name'], ENT_QUOTES, 'UTF-8'); ?>
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
                    <select name="t_subject_sec_1" class="field-select" id="t_subject_sec_1">
                        <option value="">None / Not Assigned</option>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject['sub_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php if($row['t_subject_sec_1'] == $subject['sub_id']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($subject['sub_name'], ENT_QUOTES, 'UTF-8'); ?>
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
                    <select name="t_subject_sec_2" class="field-select" id="t_subject_sec_2">
                        <option value="">None / Not Assigned</option>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject['sub_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php if($row['t_subject_sec_2'] == $subject['sub_id']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($subject['sub_name'], ENT_QUOTES, 'UTF-8'); ?>
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
                    <textarea name="t_address" id="t_address" class="field-textarea"
                              placeholder="Enter complete address"><?php echo htmlspecialchars($row['t_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <!-- About -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-info-circle"></i>
                        About the Teacher
                    </label>
                    <textarea name="t_about" id="t_about" class="field-textarea"
                              placeholder="Enter brief info about the teacher"><?php echo htmlspecialchars($row['t_about'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

            </div><!-- /.form-grid -->

            <div class="form-footer">
                <button type="submit" name="submit" value="submit" class="btn btn-success">
                    Update Teacher Profile
                </button>
                <a href="edit_teacher_profile.php?t_id=<?php echo intval($row['t_id']); ?>"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</main>

<?php include('../Include/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. Show/hide "Class Teacher Of" on load and on change ─────────
    const tRoleSelect    = document.getElementById('t_role');
    const teacherTypeDiv = document.getElementById('teacher_type');
    const tClassSelect   = document.getElementById('t_class');

    function toggleClassField() {
        if (tRoleSelect.value === 'Class Teacher') {
            teacherTypeDiv.style.display = 'block';
        } else {
            teacherTypeDiv.style.display = 'none';
            tClassSelect.value = '';
        }
    }

    toggleClassField();
    tRoleSelect.addEventListener('change', toggleClassField);

    // ── 2. Phone — digits only ─────────────────────────────────────────
    const phoneInput = document.getElementById('t_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    }

    // ── 3. Profile image live preview ─────────────────────────────────
    const imageUpload = document.getElementById('imageUpload');
    const profileImage = document.getElementById('profileImage');

    imageUpload.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (file.size > 5 * 1024 * 1024) {
                alert('Image size must not exceed 5 MB.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                profileImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

});
</script>