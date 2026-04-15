<?php
include('../Include/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL)
$t_id=$_GET['t_id'];
include('../Include/sidebar.php');
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content profile-page">
    <div class="page-header">
            <a href="../pages/teachers_page.php" class="btn btn-primary ">
              <i class="fas fa-arrow-left"></i> Go Back
            </a>
        <h1>User Profile</h1>
        <h5>Manage details that make our site work better for you, and decide what info is visible to others</h5>
    </div>

    <?php
    try{
        $SELECT="SELECT * FROM teacher_tbl WHERE t_id='$t_id'";
        $stmt=$pdo->prepare($SELECT);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
        echo "Error fetching data: ".$e->getMessage();
    }
    ?>

  
<!-- Main Content Grid -->
<div class="content-grid">
    <!-- Single Unified Profile Card -->
    <div class="profile-unified-card">
        <!-- Card Header with Gradient Background -->
        <div class="profile-card-header"></div>
        
        <!-- Profile Image -->
        <div class="profile-image-container">
            <?php
            if($row['t_img']!="" && $row['t_img']!=NULL){
            ?>
                <img src="../assets/image/<?php echo $row['t_img']; ?>" alt="User Image" class="profile-img">
            <?php
            } else {
                if($row['t_gender']=="Male"){
            ?>
                    <img src="../assets/image/male_default.jpeg" alt="User Image" class="profile-img">
            <?php
                } else if($row['t_gender']=="Female"){
            ?>
                    <img src="../assets/image/female_default.jpeg" alt="User Image" class="profile-img">
            <?php
                } else {
            ?>
                    <img src="../assets/image/default.jpg" alt="User Image" class="profile-img">
            <?php
                }
            }
            ?>
        </div>
        
        <!-- Card Body -->
        <div class="profile-card-body">
            <!-- User Details Grid -->
            <div class="user-details-grid">
                <div class="user-detail-item name">
                    <label for="t_name" id="t_"name>User Name</label>
                    <h3 class="user-detail-name"><?php echo htmlspecialchars($row['t_name'] ?? 'Teacher'); ?></h3>
                </div>
                
                <div class="user-detail-item email">
                    <label for="t_email" id="t_email">Email</label>
                    <h3 class="user-detail-email"><?php echo htmlspecialchars($row['t_email'] ?? ''); ?></h3>
                </div>
                
                <div class="user-detail-item phone">
                    <label for="t_phone" id="t_phone">Phone</label>
                    <h3 class="user-detail-phone"><?php echo htmlspecialchars($row['t_phone'] ?? 'N/A'); ?></h3>
                </div>
                
                <div class="user-detail-item address">
                    <label for="t_address" id="t_address">Address</label>
                    <h3 class="user-detail-address"><?php echo htmlspecialchars($row['t_address'] ?? 'N/A'); ?></h3>
                </div>

                <div class="user-detail-item role">
                    <label for="t_role" id="t_role">Role</label>
                    <h3 class="user-detail-role"><?php echo htmlspecialchars($row['t_role'] ?? 'N/A'); ?></h3>
                </div>
            </div>
            
            <!-- About Me Section -->
            <div class="about-me-section">
                <h3>About Me</h3>
                <p><?php echo htmlspecialchars($row['t_about'] ?? 'No information provided.'); ?></p>
            </div>
        </div>
    </div>
</div>
</main>

<?php
include('../Include/footer.php');
?>