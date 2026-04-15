<?php
$user= $_SESSION['u_name'] ?? $_SESSION['s_name'] ?? $_SESSION['t_name'] ?? 'Guest';
$gender=$_SESSION['u_gender'] ?? $_SESSION['s_gender'] ?? $_SESSION['t_gender'] ?? 'Unknown';
$userimage=$_SESSION['u_img']?? $_SESSION['s_img'] ?? $_SESSION['t_img'] ?? '../assets/image/default.jpg';
$role=$_SESSION['type'];
// Get current page to set active class
$current_page = basename($_SERVER['PHP_SELF']);
echo $role;
?>
 <!-- ============ SIDEBAR SECTION ============ -->
  <aside class="sidebar">
        <div class="user-profile">
            <div class="avatar-wrapper">
               <?php
               if($userimage!="" && $userimage!=NULL){
                ?>
                <img src="../assets/image/<?php echo $userimage; ?>" alt="User" class="user-avatar"></div>
                <?php
               } else {
               ?>
               <?php
                if($gender=="Male"){
                    ?>
                    <img src="../assets/image/male_default.jpeg" alt="User Image" class="user-avatar"></div>
                <?php
                } else if($gender=="Female"){
                    ?>
                    <img src="../assets/image/female_default.jpeg" alt="User Image" class="user-avatar"></div>
                <?php
                }else{
                ?>
                     <img src="../assets/image/default.jpg" alt="User Image" class="user-avatar"></div>
                <?php
                }
                ?>
                <?php
                }
                ?>
            <div class="user-name"><?php echo $user; ?></div>
            <div class="user-designation"><?php echo htmlspecialchars($role ?? 'User'); ?></div>
        </div>
        
        <ul class="nav-menu">
            <?php
            // Define the pages that require admin access
            $admin_pages = ['index.php', 'classes_page.php', 'subjects_page.php', 'add_article.php', 'user_profile.php'];
            // Check if the current page is in the admin pages list and if the user is an admin
            if (in_array($current_page, $admin_pages) && $role !== 'Admin') {
                // If the user is not an admin, redirect to a "403 Forbidden" page or show an error message
                header("Location: ../403.php");
                exit();
            }
            
            ?>
            <li class="nav-category">Main</li>
            <a href="../assets/index.php" class="home-logo">
            <li class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </li></a> 
              <a href="
../pages/classes_page.php" class="home-logo">
            <li class="nav-item <?php echo ($current_page == 'classes_page.php') ? 'active' : ''; ?>">
                <i class="fas fa-edit"></i>
                <span>Manage Students</span>
            </li></a>
            <a href="
../pages/subjects_page.php" class="home-logo">
            <li class="nav-item <?php echo ($current_page == 'subjects_page.php') ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Manage Teachers</span>
            </li></a>
              <a href="
../posts/add_article.php" class="home-logo">
            <li class="nav-item" <?php echo ($current_page == 'add_article.php') ? 'active' : ''; ?>">
                <i class="fas fa-puzzle-piece"></i>
                <span>Forms</span>
            </li></a>
            <li class="nav-item">
                <i class="fas fa-chart-pie"></i>
                <span>Charts</span>
            </li>
              <a href="../profile_pages/user_profile.php" class="home-logo">
             <li class="nav-item <?php echo ($current_page == 'user_profile.php') ? 'active' : ''; ?>">
                <i class="fas fa-flask"></i>
                <span>About Me</span>
            </li></a>
            <li class="nav-item">
                <i class="fas fa-icons"></i>
                <span>Icons</span>
            </li>
        </ul>
    </aside>