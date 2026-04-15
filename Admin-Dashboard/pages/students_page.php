<?php
include('../Include/header.php');
// Authentication check - redirect if not logged in
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}

// BUG FIX #1 — $s_id was read from $_SESSION['s_id'] but never used anywhere on this page.
// The page lists students by class, not by a single student's session ID.
// Removed the unused variable to avoid confusion.

$class = $_GET['class'] ?? null; // Get class from query parameter if available
$_SESSION['class'] = $class;
include('../Include/sidebar.php');
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content profile-page">
    <div class="page-header">
        <h2>List of Students</h2>
    </div>
    <div class="add_btn">
        <a href="../pages/classes_page.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
    </div>
<div class="button-container">
    <div class="add_btn">
        <a href="../add_pages/add_student_page.php" class="btn btn-primary float-end">
            <i class="fas fa-plus"></i> Add New Student
        </a>
    </div>
    <div class="add_btn">
       <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addStudentModal">
           <i class="fas fa-plus"></i> Add New Student by Excel
       </button>     
    </div>
    </div>
    <div class="profile-section">
        <?php
        try {
            if ($class !== null) {
                // Filter students by class if a class parameter is provided
                $SELECT = "SELECT * FROM student_tbl WHERE c_id = :class ORDER BY s_name ASC";
                $stmt = $pdo->prepare($SELECT);
                $stmt->bindParam(':class', $class);

            } else {
                // No class filter — fetch all students
                $SELECT = "SELECT * FROM student_tbl ORDER BY s_name ASC";
                $stmt = $pdo->prepare($SELECT);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
                    <div class="teacher-card">
                        <div class="teacher-card-image">
                            <!-- Class Badge -->
                            <div class="staff-badge">
                                <?php echo htmlspecialchars($row['s_class'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <?php
                            $student_img    = htmlspecialchars($row['s_img']    ?? '', ENT_QUOTES, 'UTF-8');
                            $student_gender = htmlspecialchars($row['s_gender'] ?? '', ENT_QUOTES, 'UTF-8');

                            if (!empty($row['s_img'])) {
                            ?>
                                <img src="../assets/image/<?php echo $student_img; ?>"
                                     alt="Student Image"
                                     class="teacher-profile-img"
                                     onerror="this.src='../assets/image/default.jpg'">
                            <?php
                            } elseif ($student_gender === 'Male') {
                            ?>
                                <img src="../assets/image/male_default.jpeg"
                                     alt="Student Image"
                                     class="teacher-profile-img">
                            <?php
                            } elseif ($student_gender === 'Female') {
                            ?>
                                <img src="../assets/image/female_default.jpeg"
                                     alt="Student Image"
                                     class="teacher-profile-img">
                            <?php
                            } else {
                            ?>
                                <img src="../assets/image/default.jpg"
                                     alt="Student Image"
                                     class="teacher-profile-img">
                            <?php
                            }
                            ?>
                            <h3 class="teacher-name">
                                <?php echo htmlspecialchars($row['s_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?>
                            </h3>
                        </div>

                        <div class="teacher-card-actions">
                            <a href="../view/view_student_profile.php?s_id=<?php echo intval($row['s_id']); ?>"
                               class="action-btn view-btn">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            <a href="../edit_and_store_pages/edit_student_profile.php?s_id=<?php echo intval($row['s_id']); ?>"
                               class="action-btn edit-btn">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <a href="../edit_and_store_pages/delete_student_page.php?s_id=<?php echo intval($row['s_id']); ?>"
                               class="action-btn delete-btn"
                               onclick="return confirm('Are you sure you want to delete this student?');">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
        <?php
                } // end while
            } else {
        ?>
               
                <p class="no-data-message">No students found in the database.</p>
        <?php
            }
        } catch (PDOException $e) {
            error_log("Database error in students_page.php: " . $e->getMessage());
        ?>
            <div class="alert alert-danger">
                <p>An error occurred while fetching student data. Please try again later.</p>
            </div>
        <?php
        }
        ?>
    </div>
</main>

<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">
                    <i class="fas fa-file-excel"></i> Add New Students by Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="excelImportForm" action="../../Mail/import_excel.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="mb-3">
                        <label for="import_file" class="form-label fw-semibold">
                            <i class="fas fa-upload"></i> Upload Excel File
                        </label>

                        <!-- Visible input row -->
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="import_file_display"
                                   placeholder="No file chosen"
                                   readonly
                                   onclick="document.getElementById('import_file').click()">
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="document.getElementById('import_file').click()">
                                <i class="fas fa-folder-open"></i> Browse
                            </button>
                        </div>

                        <!-- Hidden real file input -->
                        <input type="file"
                               id="import_file"
                               name="import_file"
                               accept=".xlsx, .xls, .csv"
                               style="display: none;"
                               required>

                        <small class="form-text text-muted mt-1 d-block">
                            Accepted formats: <strong>.xlsx, .xls, .csv</strong> &nbsp;|&nbsp; Max size: <strong>5MB</strong>
                        </small>
                    </div>

                    <!-- <div class="mt-2">
                        <a href="../../assets/templates/student_import_template.xlsx" download class="text-decoration-none" style="font-size: 0.85em;">
                            <i class="fas fa-download"></i> Download sample template
                        </a>
                    </div> -->

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="submit" form="excelImportForm" name="save_excel_data" class="btn btn-primary">
                    <i class="fas fa-file-import"></i> Import Students
                </button>
            </div>

        </div>
    </div>
</div

<?php include('../Include/footer.php'); ?>
<script>
    document.getElementById('import_file').addEventListener('change', function () {
        const display = document.getElementById('import_file_display');
        if (this.files && this.files[0]) {
            const file = this.files[0];

            // 5MB guard
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB. Please upload a smaller file.');
                this.value = '';
                display.value = '';
                return;
            }

            display.value = file.name;
        } else {
            display.value = '';
        }
    });
</script>