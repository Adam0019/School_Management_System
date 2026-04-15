<?php
include('../Include/header.php');
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}
include('../Include/sidebar.php');
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content profile-page">
    <div class="page-header">
        <h2>List of Teaching Staff by Subject</h2>
    </div>

    <div class="add_btn">
        <a href="../add_pages/add_subject_page.php" class="btn btn-primary float-end">
            <i class="fas fa-plus"></i> Add New Subject
        </a>
    </div>
    <div class="add_btn">
        <a href="../add_pages/add_teacher_page.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Teacher
        </a>
    </div>

    <div class="profile-section">
        <?php
        try {
            $SELECT = "SELECT * FROM subject_tbl ORDER BY sub_name ASC";
            $stmt = $pdo->prepare($SELECT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
                    <div class="teacher-card">
                        <div class="teacher-card-image">
                            <!-- Subject Badge -->
                            <div class="staff-badge">
                                <?php echo htmlspecialchars($row['sub_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?>
                            </div>

                            <!-- Subject Image -->
                            <?php
                            $subject_img = htmlspecialchars($row['sub_img'] ?? '', ENT_QUOTES, 'UTF-8');
                            if (!empty($row['sub_img'])) {
                            ?>
                                <img src="../assets/image/<?php echo $subject_img; ?>"
                                     alt="Subject Image"
                                     class="teacher-profile-img"
                                     onerror="this.src='../assets/image/subject_default.jpg'">
                            <?php } else { ?>
                                <img src="../assets/image/subject_default.jpg"
                                     alt="Subject Image"
                                     class="teacher-profile-img">
                            <?php } ?>

                            <h3 class="teacher-name">
                                <?php echo htmlspecialchars($row['sub_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?>
                            </h3>
                        </div>

                        <div class="teacher-card-actions">
                         
                            <a href="../pages/teachers_page.php?subject=<?php echo intval($row['sub_id']); ?>"
                               class="action-btn view-btn" title="View Teachers">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            <a href="../edit_and_store_pages/edit_subject_profile.php?sub_id=<?php echo intval($row['sub_id']); ?>"
                               class="action-btn edit-btn" title="Edit Subject">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <a href="../edit_and_store_pages/delete_subject_page.php?sub_id=<?php echo intval($row['sub_id']); ?>"
                               class="action-btn delete-btn"
                               title="Delete Subject"
                               onclick="return confirm('Are you sure you want to delete this subject?');">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
        <?php
                } // end while
            } else {
        ?>
                
                <p class="no-data-message">No subjects found in the database.</p>
        <?php
            }
        } catch(PDOException $e) {
            error_log("Database error in subjects_page.php: " . $e->getMessage());
        ?>
            <div class="alert alert-danger">
                <p>An error occurred while fetching subject data. Please try again later.</p>
            </div>
        <?php
        }
        ?>
    </div>
</main>

<?php include('../Include/footer.php'); ?>