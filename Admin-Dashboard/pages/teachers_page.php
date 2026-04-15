<?php
include('../Include/header.php');

// Authentication check - redirect if not logged in
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}

$subject = $_GET['subject'] ?? null; // numeric sub_id from subjects_page.php

include('../Include/sidebar.php');
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content profile-page">
    <div class="page-header">
        <h2>List of Teaching Staff</h2>
    </div>

    <div class="add_btn">
        <a href="../add_pages/add_teacher_page.php" class="btn btn-primary float-end">
            <i class="fas fa-plus"></i> Add New Teacher
        </a>
    </div>
    <div class="add_btn">
        <a href="../pages/subjects_page.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
    </div>

    <div class="profile-section">
        <?php
        try {
            if ($subject !== null) {
                // Filter by subject ID if coming from subjects_page.php
                $SELECT = "SELECT * FROM teacher_tbl WHERE t_sub_id = :subject ORDER BY t_name ASC";
                $stmt = $pdo->prepare($SELECT);
                $stmt->bindParam(':subject', $subject);
            } else {
                // No filter — show all teachers
                $SELECT = "SELECT * FROM teacher_tbl ORDER BY t_name ASC";
                $stmt = $pdo->prepare($SELECT);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
                    <div class="teacher-card">
                        <div class="teacher-card-image">
                            <!-- Staff Badge -->
                            <div class="staff-badge">
                                <?php echo htmlspecialchars($row['t_role'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <?php
                            $teacher_img    = htmlspecialchars($row['t_img']    ?? '', ENT_QUOTES, 'UTF-8');
                            $teacher_gender = htmlspecialchars($row['t_gender'] ?? '', ENT_QUOTES, 'UTF-8');

                            if (!empty($row['t_img'])) {
                            ?>
                                <img src="../assets/image/<?php echo $teacher_img; ?>"
                                     alt="Teacher Image"
                                     class="teacher-profile-img"
                                     onerror="this.src='../assets/image/default.jpg'">
                            <?php } elseif ($teacher_gender === 'Male') { ?>
                                <img src="../assets/image/male_default.jpeg"
                                     alt="Teacher Image"
                                     class="teacher-profile-img">
                            <?php } elseif ($teacher_gender === 'Female') { ?>
                                <img src="../assets/image/female_default.jpeg"
                                     alt="Teacher Image"
                                     class="teacher-profile-img">
                            <?php } else { ?>
                                <img src="../assets/image/default.jpg"
                                     alt="Teacher Image"
                                     class="teacher-profile-img">
                            <?php } ?>

                            <h3 class="teacher-name">
                                <?php echo htmlspecialchars($row['t_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?>
                            </h3>
                        </div>

                        <div class="teacher-card-actions">
                            <a href="../view/view_teacher_profile.php?t_id=<?php echo intval($row['t_id']); ?>"
                               class="action-btn view-btn">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            <a href="../edit_and_store_pages/edit_teacher_profile.php?t_id=<?php echo intval($row['t_id']); ?>"
                               class="action-btn edit-btn">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <a href="../edit_and_store_pages/delete_teacher_page.php?t_id=<?php echo intval($row['t_id']); ?>"
                               class="action-btn delete-btn"
                               onclick="return confirm('Are you sure you want to delete this teacher?');">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
        <?php
                } // end while
            } else {
        ?>
                <!-- BUG FIX #2 — echo outside PHP tags printed as raw text; replaced with plain HTML -->
                <p class="no-data-message">No teachers found in the database.</p>
        <?php
            }
        } catch(PDOException $e) {
            error_log("Database error in teachers_page.php: " . $e->getMessage());
        ?>
            <div class="alert alert-danger">
                <p>An error occurred while fetching teacher data. Please try again later.</p>
            </div>
        <?php
        }
        ?>
    </div>
</main>

<?php include('../Include/footer.php'); ?>