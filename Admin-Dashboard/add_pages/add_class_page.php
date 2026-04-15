<?php
include('../Include/header.php');

if ($_SESSION['userAuth'] != "" && $_SESSION['userAuth'] != NULL) {
    include('../Include/sidebar.php');
    $user = $_SESSION['u_name'];
?>

<!-- ============ MAIN CONTENT SECTION ============ -->

<?php
// Fetch subjects
try {
    $query = "SELECT * FROM subject_tbl";
    $stmt  = $pdo->prepare($query);
    $stmt->execute();
    $subs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching subjects: " . htmlspecialchars($e->getMessage());
    $subs = [];
}
?>

<main class="main-content">
    <div class="form-container">

        <!-- Go Back -->
        <a href="../pages/classes_page.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>

        <!-- Session Messages -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="form-header">
            <h2>Add New Class</h2>
            <p>Fill in the details below to add a new class to the system</p>
        </div>

        <form action="../edit_and_store_pages/store_class.php" method="POST" enctype="multipart/form-data">

            <div class="form-grid">

                <!-- Class Name -->
                <div class="field-card">
                    <label class="field-label" for="c_name">
                        <i class="fas fa-chalkboard"></i>
                        Class Name
                    </label>
                    <input type="text" name="c_name" id="c_name" class="field-input"
                           placeholder="Enter class name" required minlength="2">
                </div>

                <!-- sub_id: hidden field synced to sub_one via JS (satisfies FK constraint) -->
                <input type="hidden" name="sub_id" id="sub_id" value="">

                <!-- Subject dropdowns (sub_one to sub_nine) -->
                <?php
                $subjectFields = [
                    'sub_one'   => 'Subject 1',
                    'sub_two'   => 'Subject 2',
                    'sub_three' => 'Subject 3',
                    'sub_four'  => 'Subject 4',
                    'sub_five'  => 'Subject 5',
                    'sub_six'   => 'Subject 6',
                    'sub_seven' => 'Subject 7',
                    'sub_eight' => 'Subject 8',
                    'sub_nine'  => 'Subject 9 (Optional)',
                ];
                foreach ($subjectFields as $fieldName => $fieldLabel):
                    $isOptional = ($fieldName === 'sub_nine');
                ?>
                <div class="field-card">
                    <label class="field-label" for="<?php echo $fieldName; ?>">
                        <i class="fas fa-book"></i>
                        <?php echo htmlspecialchars($fieldLabel); ?>
                    </label>
                    <select name="<?php echo $fieldName; ?>"
                            id="<?php echo $fieldName; ?>"
                            class="field-select"
                            <?php echo $isOptional ? '' : 'required'; ?>>
                        <option value="None" selected>None</option>
                        <?php foreach ($subs as $sub): ?>
                            <option value="<?php echo htmlspecialchars($sub['sub_id']); ?>">
                                <?php echo htmlspecialchars($sub['sub_name']); ?>
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
                    <input type="text" name="section_a" id="section_a" class="field-input"
                           placeholder="Enter section name" required minlength="2">
                </div>

                    <!-- Class Section B -->
                <div class="field-card">
                    <label class="field-label" for="section_b">
                        <i class="fas fa-layer-group"></i>
                        Class Section
                    </label>
                    <input type="text" name="section_b" id="section_b" class="field-input"
                           placeholder="Enter section name" required minlength="2">
                </div>

                    <!-- Class Section C -->
                <div class="field-card">
                    <label class="field-label" for="section_c">
                        <i class="fas fa-layer-group"></i>
                        Class Section
                    </label>
                    <input type="text" name="section_c" id="section_c" class="field-input"
                           placeholder="Enter section name" required minlength="2">
                </div>

                    <!-- Class Section D -->
                <div class="field-card">
                    <label class="field-label" for="section_d">
                        <i class="fas fa-layer-group"></i>
                        Class Section
                    </label>
                    <input type="text" name="section_d" id="section_d" class="field-input"
                           placeholder="Enter section name" required minlength="2">
                </div>

                <!-- Class Image -->
                <div class="field-card full-width">
                    <label class="field-label" for="c_img">
                        <i class="fas fa-image"></i>
                        Class Image
                    </label>
                    <div class="file-input-wrapper">
                        <div class="field-file">
                            <label class="file-label" for="c_img">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span id="file-label-text">Click to upload or drag and drop</span>
                            </label>
                            <input type="file" name="c_img" id="c_img"
                                   accept=".jpg,.jpeg,.png,.gif" class="file-input">
                        </div>
                        <span class="file-hint">Upload a clear image of the class (JPG, PNG, GIF - max 2MB)</span>
                    </div>
                </div>

            </div><!-- /.form-grid -->

            <div class="form-actions">
                <a href="add_class_page.php">
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                         Cancel
                    </button>
                </a>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                     Save Class
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
    // ── File input: show selected filename ───────────────────────────────────
    const fileInput = document.getElementById('c_img');
    const fileLabel = document.getElementById('file-label-text');

    fileInput.addEventListener('change', function () {
        fileLabel.textContent = this.files.length > 0
            ? this.files[0].name
            : 'Click to upload or drag and drop';
    });

    // ── Subject Dropdown Filtering ───────────────────────────────────────────
    // When a subject is picked in one dropdown, it disappears from all others.
    // Switching back to None restores it everywhere.

    const subjectSelects = Array.from(
        document.querySelectorAll('.field-select[id^="sub_"]')
    );

    const subIdInput   = document.getElementById('sub_id');
    const subOneSelect = document.getElementById('sub_one');

    // Sync hidden sub_id FK field with whatever sub_one is set to
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
                if (opt.value === 'None') return; // always keep None visible

                // Hide if this value is selected in a different dropdown
                const takenElsewhere = selected.has(opt.value) && opt.value !== currentValue;
                opt.hidden   = takenElsewhere;
                opt.disabled = takenElsewhere;
            });
        });
    }

    // Attach listeners
    subjectSelects.forEach(function (sel) {
        sel.addEventListener('change', function () {
            refreshOptions();
            syncSubId();
        });
    });

    // Initialize on page load
    refreshOptions();
    syncSubId();
</script>
