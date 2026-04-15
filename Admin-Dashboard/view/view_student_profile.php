<?php
include('../Include/header.php');
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}

$class = $_SESSION['class'];
include('../Include/sidebar.php');

// BUG FIX #1 — validate s_id before use
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

// Fetch classes to resolve stored c_id into a class name
try {
    $cStmt = $pdo->prepare("SELECT * FROM class_tbl ORDER BY c_name ASC");
    $cStmt->execute();
    $classes = $cStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $classes = [];
}
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content">
    <div class="form-container">
        <a href="../pages/students_page.php?class=<?php echo $class; ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
        <div class="button-container">
            <div class="add_btn">
                <a href="../edit_and_store_pages/edit_student_profile.php?s_id=<?php echo $s_id; ?>"
                   class="btn btn-primary float-end">
                    <i class="fas fa-edit"></i> Edit Student Info
                </a>
            </div>
                <div class="add_btn">
            <a href="../edit_and_store_pages/edit_student_profile.php?s_id=<?php echo $s_id; ?>"
               class="btn btn-info float-end">
                <i class="fas fa-download"></i> Student I-CARD
            </a>
        </div>
        </div>

        <div class="form-header">
            <h2>View Student Details</h2>
            <p>The details of the student's info in the system</p>
        </div>

        <!-- Profile Picture -->
        <div class="profile-unified-card" style="margin-bottom: 4rem;">
            <div class="profile-image-container" style="margin-top: 10px; position: relative;">
                <?php if (!empty($row['s_img'])): ?>
                    <img src="../assets/image/<?php echo htmlspecialchars($row['s_img'], ENT_QUOTES, 'UTF-8'); ?>"
                         alt="Student Image" class="profile-img">
                <?php elseif ($row['s_gender'] === 'Male'): ?>
                    <img src="../assets/image/male_default.jpeg" alt="Student Image" class="profile-img">
                <?php elseif ($row['s_gender'] === 'Female'): ?>
                    <img src="../assets/image/female_default.jpeg" alt="Student Image" class="profile-img">
                <?php else: ?>
                    <img src="../assets/image/default.jpg" alt="Student Image" class="profile-img">
                <?php endif; ?>
            </div>
        </div>

        <!-- BUG FIX #2 — view page needs no <form>; replaced with a plain <div> -->
        <div id="studentView">
            <div class="form-grid">

                <!-- Student Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user"></i>
                        Student Name
                    </label>
                    <input type="text" class="field-input"
                           value="<?php echo htmlspecialchars($row['s_name'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Gender -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-venus-mars"></i>
                        Gender
                    </label>
                    <select class="field-select" disabled>
                        <option value="Male"   <?php if($row['s_gender']==="Male")   echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if($row['s_gender']==="Female") echo "selected"; ?>>Female</option>
                        <option value="Other"  <?php if($row['s_gender']==="Other")  echo "selected"; ?>>Other</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <!-- BUG FIX #3 — id was "s_id", clashing with the student ID. Removed entirely
                     (view page doesn't need input IDs). -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-calendar-alt"></i>
                        Date of Birth
                    </label>
                    <input type="date" class="field-input"
                           value="<?php echo htmlspecialchars($row['s_dob'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Guardian Name -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-friends"></i>
                        Guardian Name
                    </label>
                    <input type="text" class="field-input"
                           value="<?php echo htmlspecialchars($row['s_g_name'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Guardian Relation -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-user-shield"></i>
                        Guardian Relation
                    </label>
                    <select class="field-select" disabled>
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
                    <input type="text" class="field-input"
                           value="<?php echo htmlspecialchars($row['s_phone'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Class -->
                <!-- BUG FIX #4 — hardcoded class name strings replaced with dynamic DB lookup,
                     matching by c_id (the value stored in s_class). -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-school"></i>
                        Class
                    </label>
                    <select class="field-select" disabled>
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
                    <input type="text" class="field-input"
                           value="<?php echo htmlspecialchars($row['s_roll'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Section -->
                <div class="field-card">
                    <label class="field-label">
                        <i class="fas fa-chalkboard"></i>
                        Section
                    </label>
                    <input type="text" class="field-input"
                           value="<?php echo htmlspecialchars($row['s_section'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </div>

                <!-- Address -->
                <div class="field-card full-width">
                    <label class="field-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Address
                    </label>
                    <textarea class="field-textarea" disabled><?php echo htmlspecialchars($row['s_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

            </div><!-- /.form-grid -->
        </div><!-- /#studentView -->
    </div>
</main>

<?php include('../Include/footer.php'); ?>