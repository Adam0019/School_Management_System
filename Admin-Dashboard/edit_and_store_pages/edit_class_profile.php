<?php
include('../Include/header.php');

if (!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}

include('../Include/sidebar.php');
$user = $_SESSION['u_name'];

// Validate c_id from URL
$c_id = isset($_GET['c_id']) ? intval($_GET['c_id']) : 0;

if ($c_id <= 0) {
    $_SESSION['error'] = 'No class ID provided.';
    header('Location: ../pages/classes_page.php');
    exit;
}

// Fetch class details
try {
    $stmt = $pdo->prepare("SELECT * FROM class_tbl WHERE c_id = :c_id");
    $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
    $stmt->execute();
    $class = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$class) {
        $_SESSION['error'] = 'Class not found.';
        header('Location: ../pages/classes_page.php');
        exit;
    }
} catch (PDOException $e) {
    error_log("Error fetching class: " . $e->getMessage());
    $_SESSION['error'] = 'Error fetching class details. Please try again.';
    header('Location: ../pages/classes_page.php');
    exit;
}

// Fetch all subjects for dropdowns
try {
    $stmt_subjects = $pdo->prepare("SELECT sub_id, sub_name FROM subject_tbl ORDER BY sub_name ASC");
    $stmt_subjects->execute();
    $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching subjects: " . $e->getMessage());
    $subjects = [];
}

$classImg = !empty($class['c_img'])
    ? htmlspecialchars($class['c_img'], ENT_QUOTES, 'UTF-8')
    : 'class_default.jpg';
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content">
    <div class="form-container">

        <!-- Go Back -->
        <a href="../pages/classes_page.php">
            <button type="button" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Go Back
            </button>
        </a>

        <!-- Session Messages -->
        <!-- <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?> -->



        <div class="form-header">
            <h2>Edit Class Details</h2>
            <p>Update the class information below</p>
        </div>

        <!-- Class Image Preview -->
         <!-- Profile Picture Display Section -->
        <div class="profile-unified-card" style="margin-bottom: 4rem;">
            <!-- Card Header with Gradient Background -->
          
            
            <!-- Profile Image -->
            <div class="profile-image-container" style="margin-top: 10px; position: relative;">
                <?php
                if($classImg!="" && $classImg!=NULL){
                ?>
                    <img src="../assets/image/<?php echo $classImg; ?>" alt="Class Image" class="profile-img" id="profileImage">
                <?php
                    } else {
                ?>
                        <img src="../assets/image/class_default.jpg" alt="Class Image" class="profile-img" id="profileImage">
                <?php
                    }
                
                ?>
             <!-- Edit Button Overlay -->
                <label for="imageUpload" class="profile-edit-btn">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="imageUpload" name="c_img" accept="image/*" style="display: none;" form="classForm">
            </div>
            
            
        </div>

        <form id="classForm" action="../edit_and_store_pages/update_class_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="c_id" value="<?php echo $c_id; ?>">
            <!-- sub_id synced to sub_one via JS -->
            <input type="hidden" name="sub_id" id="sub_id" value="<?php echo htmlspecialchars($class['sub_one'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-grid">

                <!-- Class Name -->
                <div class="field-card">
                    <label class="field-label" for="c_name">
                        <i class="fas fa-chalkboard"></i>
                        Class Name
                    </label>
                    <input type="text" name="c_name" id="c_name" class="field-input"
                           placeholder="Enter class name" minlength="2" required
                           value="<?php echo htmlspecialchars($class['c_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <?php
                // Subject fields config: name => [label, required]
                $subjectFields = [
                    'sub_one'   => ['Subject 1', true],
                    'sub_two'   => ['Subject 2', true],
                    'sub_three' => ['Subject 3', true],
                    'sub_four'  => ['Subject 4', true],
                    'sub_five'  => ['Subject 5', true],
                    'sub_six'   => ['Subject 6', true],
                    'sub_seven' => ['Subject 7', true],
                    'sub_eight' => ['Subject 8', true],
                    'sub_nine'  => ['Subject 9 (Optional)', false],
                ];

                foreach ($subjectFields as $fieldName => [$fieldLabel, $isRequired]):
                ?>
                <div class="field-card">
                    <label class="field-label" for="<?php echo $fieldName; ?>">
                        <i class="fas fa-book"></i>
                        <?php echo htmlspecialchars($fieldLabel); ?>
                    </label>
                    <select name="<?php echo $fieldName; ?>"
                            id="<?php echo $fieldName; ?>"
                            class="field-select"
                            <?php echo $isRequired ? 'required' : ''; ?>>
                        <option value="None">None</option>
                        <?php foreach ($subjects as $subject):
                            $selected = (isset($class[$fieldName]) && $class[$fieldName] == $subject['sub_id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($subject['sub_id'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($subject['sub_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endforeach; ?>

                <!-- Class Section A -->
                <div class="field-card">
                    <label class="field-label" for="section_a">
                        <i class="fas fa-layer-group"></i>
                        Class Section
                    </label>
                    <select name="section_a" id="section_a" class="field-select" required>
                        <option value="A" <?php echo (isset($class['section_a']) && $class['section_a'] == 'A') ? 'selected' : ''; ?>>Section A</option>
                        <option value="B" <?php echo (isset($class['section_a']) && $class['section_a'] == 'B') ? 'selected' : ''; ?>>Section B</option>
                        <option value="C" <?php echo (isset($class['section_a']) && $class['section_a'] == 'C') ? 'selected' : ''; ?>>Section C</option>
                        <option value="D" <?php echo (isset($class['section_a']) && $class['section_a'] == 'D') ? 'selected' : ''; ?>>Section D</option>
                    </select>
                    

            </div><!-- /.form-grid -->

            <div class="form-actions">
                <a href="../pages/classes_page.php">
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </a>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Class
                </button>
            </div>

        </form>
    </div>
</main>

<?php include('../Include/footer.php'); ?>

<script>
    // ── Image preview on file select ─────────────────────────────────────────
    const imageUpload = document.getElementById('imageUpload');
    const profileImage = document.getElementById('profileImage');

    imageUpload.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                profileImage.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // ── Subject Dropdown Filtering ───────────────────────────────────────────
    // Prevents the same subject being selected in multiple dropdowns

    const subjectSelects = Array.from(
        document.querySelectorAll('.field-select[id^="sub_"]')
    );
    const subIdInput   = document.getElementById('sub_id');
    const subOneSelect = document.getElementById('sub_one');

    function syncSubId() {
        subIdInput.value = (subOneSelect.value !== 'None') ? subOneSelect.value : '';
    }

    function getSelectedValues() {
        return new Set(
            subjectSelects
                .map(sel => sel.value)
                .filter(val => val !== 'None')
        );
    }

    function refreshOptions() {
        const selected = getSelectedValues();
        subjectSelects.forEach(function (sel) {
            const currentValue = sel.value;
            Array.from(sel.options).forEach(function (opt) {
                if (opt.value === 'None') return;
                const takenElsewhere = selected.has(opt.value) && opt.value !== currentValue;
                opt.hidden   = takenElsewhere;
                opt.disabled = takenElsewhere;
            });
        });
    }

    subjectSelects.forEach(function (sel) {
        sel.addEventListener('change', function () {
            refreshOptions();
            syncSubId();
        });
    });

    // Initialize on page load (pre-filled values need filtering too)
    refreshOptions();
    syncSubId();
</script>
