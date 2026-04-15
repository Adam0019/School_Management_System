<?php
include('../Include/header.php');
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}
include('../Include/sidebar.php');

// BUG FIX #1 — validate t_id before use
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

// Fetch classes to resolve the stored c_id into a class name
try {
    $cStmt = $pdo->prepare("SELECT * FROM class_tbl ORDER BY c_name ASC");
    $cStmt->execute();
    $classes = $cStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $classes = [];
}

// Fetch subjects to resolve stored sub_ids into subject names
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
        <a href="../edit_and_store_pages/edit_teacher_profile.php?t_id=<?php echo $t_id; ?>"
           class="btn btn-primary float-end">
            <i class="fas fa-edit"></i> Edit Teacher Info
        </a>

        <div class="form-header">
            <h2>View Teacher Details</h2>
            <p>The details of the teacher's info in the system</p>
        </div>

        <!-- Profile Picture -->
        <div class="profile-unified-card" style="margin-bottom: 4rem;">
            <div class="profile-image-container" style="margin-top: 10px; position: relative;">
                <?php if (!empty($row['t_img'])): ?>
                    <img src="../assets/image/<?php echo htmlspecialchars($row['t_img'], ENT_QUOTES, 'UTF-8'); ?>"
                         alt="Teacher Image" class="profile-img">
                <?php elseif ($row['t_gender'] === 'Male'): ?>
                    <img src="../assets/image/male_default.jpeg" alt="Teacher Image" class="profile-img">
                <?php elseif ($row['t_gender'] === 'Female'): ?>
                    <img src="../assets/image/female_default.jpeg" alt="Teacher Image" class="profile-img">
                <?php else: ?>
                    <img src="../assets/image/default.jpg" alt="Teacher Image" class="profile-img">
                <?php endif; ?>
            </div>
        </div>

        <!-- BUG FIX #2 — view page doesn't need a <form> at all since nothing is submitted.
             Replaced with a plain <div> to avoid a pointless form with action="#". -->
        <div id="teacherView">
            <div class="form-grid">

                <!-- Teacher Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Teacher Name
                    </label>
                    <input type="text" class="field-input"
                           value="<?php echo htmlspecialchars($row['t_name'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Gender -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-venus-mars"></i>
                        Gender
                    </label>
                    <select class="field-select" disabled>
                        <option value="Male"   <?php if($row['t_gender']==="Male")   echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if($row['t_gender']==="Female") echo "selected"; ?>>Female</option>
                        <option value="Other"  <?php if($row['t_gender']==="Other")  echo "selected"; ?>>Other</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <!-- BUG FIX #3 — id was "t_id", clashing with the teacher ID. Changed to "t_dob". -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date of Birth
                    </label>
                    <input type="date" id="t_dob" class="field-input"
                           value="<?php echo htmlspecialchars($row['t_dob'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Phone -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-phone"></i>
                        Phone Number
                    </label>
                    <input type="tel" class="field-input"
                           value="<?php echo htmlspecialchars($row['t_phone'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Email -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" class="field-input"
                           value="<?php echo htmlspecialchars($row['t_email'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Role -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-tag"></i>
                        Teacher Role
                    </label>
                    <select class="field-select" disabled>
                        <option value="Class Teacher"   <?php if($row['t_role']==="Class Teacher")   echo "selected"; ?>>Class Teacher</option>
                        <option value="Para Teacher"    <?php if($row['t_role']==="Para Teacher")    echo "selected"; ?>>Para Teacher</option>
                        <option value="Subject Teacher" <?php if($row['t_role']==="Subject Teacher") echo "selected"; ?>>Subject Teacher</option>
                        <option value="Games Teacher"   <?php if($row['t_role']==="Games Teacher")   echo "selected"; ?>>Games Teacher</option>
                    </select>
                </div>

                <!-- Class Teacher Of -->
                <!-- BUG FIX #4 — hardcoded class name strings replaced with dynamic DB lookup -->
                <?php if (!empty($row['t_class'])): ?>
                <div class="field-card" id="teacher_type">
                    <label class="field-label">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Class Teacher Of
                    </label>
                    <select class="field-select" disabled>
                        <?php foreach($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class['c_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php if($row['t_class'] == $class['c_id']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($class['c_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Subject Main -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-school"></i>
                        Subject Main
                    </label>
                    <select class="field-select" disabled>
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
                    <select class="field-select" disabled>
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
                    <select class="field-select" disabled>
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
                    <textarea class="field-textarea" disabled><?php echo htmlspecialchars($row['t_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <!-- About -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-info-circle"></i>
                        About the Teacher
                    </label>
                    <textarea class="field-textarea" disabled><?php echo htmlspecialchars($row['t_about'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

            </div><!-- /.form-grid -->
        </div><!-- /#teacherView -->
    </div>
</main>

<?php include('../Include/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Show "Class Teacher Of" field on load if role is Class Teacher
    const tRoleSelect    = document.getElementById('t_role');
    const teacherTypeDiv = document.getElementById('teacher_type');
    if (tRoleSelect && teacherTypeDiv) {
        if (tRoleSelect.value === 'Class Teacher') {
            teacherTypeDiv.style.display = 'block';
        }
    }
});
</script>