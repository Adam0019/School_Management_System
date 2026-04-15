<?php
include('../Include/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
$u_id=$_SESSION['u_id'];


include('../Include/sidebar.php');
?>

<!-- ============ MAIN CONTENT SECTION ============ -->
<main class="main-content profile-page">
    <div class="page-header">
        <h1>User Profile</h1>
        <h5>Manage details that make our site work better for you, and decide what info is visible to others</h5>
    </div>

    <?php
    try{
        $SELECT="SELECT * FROM user_tbl WHERE u_id='$u_id'";
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
            if($row['u_img']!="" && $row['u_img']!=NULL){
            ?>
                <img src="../assets/image/<?php echo $row['u_img']; ?>" alt="User Image" class="profile-img">
            <?php
            } else {
                if($row['u_gender']=="Male"){
            ?>
                    <img src="../assets/image/male_default.jpeg" alt="User Image" class="profile-img">
            <?php
                } else if($row['u_gender']=="Female"){
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
                    <label for="u_name" id="u_"name>User Name</label>
                    <h3 class="user-detail-name"><?php echo htmlspecialchars($row['u_name'] ?? 'User'); ?></h3>
                </div>
                
                <div class="user-detail-item email">
                    <label for="u_email" id="u_email">Email</label>
                    <h3 class="user-detail-email"><?php echo htmlspecialchars($row['u_email'] ?? ''); ?></h3>
                </div>
                
                <div class="user-detail-item phone">
                    <label for="u_phone" id="u_phone">Phone</label>
                    <h3 class="user-detail-phone"><?php echo htmlspecialchars($row['u_phone'] ?? 'N/A'); ?></h3>
                </div>
                
                <div class="user-detail-item address">
                    <label for="u_address" id="u_address">Address</label>
                    <h3 class="user-detail-address"><?php echo htmlspecialchars($row['u_address'] ?? 'N/A'); ?></h3>
                </div>
            </div>
            
            <!-- About Me Section -->
            <div class="about-me-section">
                <h3>About Me</h3>
                <p><?php echo htmlspecialchars($row['u_about'] ?? 'No information provided.'); ?></p>
            </div>
        </div>
    </div>
</div>
</main>

<?php
}
include('../Include/footer.php');
?>