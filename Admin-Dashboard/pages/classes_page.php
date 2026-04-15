<?php
include('../Include/header.php');

if (!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    header('Location: ../login.php');
    exit;
}

include('../Include/sidebar.php');
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content profile-page">

    <div class="page-header">
        <h2>Open a class to check students</h2>
    </div>

    <!-- Action Buttons -->
    <div class="add_btn">
        <a href="../add_pages/add_class_page.php" class="btn btn-primary float-end">
                <i class="fas fa-plus"></i> Add New Class
        </a>
        <a href="../add_pages/add_student_page.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Student
        </a>
    </div>

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

    <div class="profile-section">
        <?php
        try {
            $SELECT = "SELECT * FROM class_tbl ORDER BY c_name ASC";
            $stmt   = $pdo->prepare($SELECT);
            $stmt->execute();
            $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($classes) > 0):
                foreach ($classes as $row):
                    $className = htmlspecialchars($row['c_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
                    $classImg  = !empty($row['c_img']) ? htmlspecialchars($row['c_img'], ENT_QUOTES, 'UTF-8') : 'class_default.jpg';
                    $classId   = intval($row['c_id']);
        ?>
                    <div class="teacher-card">
                        <div class="teacher-card-image">

                            <!-- Class Badge -->
                            <div class="staff-badge"><?php echo $className; ?></div>

                            <!-- Class Image -->
                            <img src="../assets/image/<?php echo $classImg; ?>"
                                 alt="Class Image"
                                 class="teacher-profile-img"
                                 onerror="this.src='../assets/image/class_default.jpg'">

                            <h3 class="teacher-name"><?php echo $className; ?></h3>
                        </div>

                        <div class="teacher-card-actions">
                            <!-- View students in this class -->
                            
                            <a href="../pages/students_page.php?class=<?php echo $classId; ?>"
                               class="action-btn view-btn" title="View Students">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            <!-- Edit this class -->
                            <a href="../edit_and_store_pages/edit_class_profile.php?c_id=<?php echo $classId; ?>"
                               class="action-btn edit-btn" title="Edit Class">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <!-- Delete this class -->
                            <a href="../edit_and_store_pages/delete_class.php?c_id=<?php echo $classId; ?>"
                               class="action-btn delete-btn"
                               title="Delete Class"
                               onclick="return confirm('Are you sure you want to delete the class \'<?php echo addslashes($row['c_name']); ?>\'? This cannot be undone.');">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>

        <?php
                endforeach;
            else:
        ?>
                <p class="no-data-message">No classes found in the database.</p>
        <?php
            endif;

        } catch (PDOException $e) {
            error_log("Database error in classes_page.php: " . $e->getMessage());
        ?>
            <div class="alert alert-danger">
                <p>An error occurred while fetching class data. Please try again later.</p>
            </div>
        <?php
        }
        ?>
    </div>

</main>

<?php include('../Include/footer.php'); ?>