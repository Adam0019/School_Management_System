<?php
include('../../Config/dbcon.php');
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- bootstrap cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- css files -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/form.css">

    <!-- summernote cdn -->
    
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    
</head>
<body>
      <!-- Overlay for closing dropdown -->
    <div class="dropdown-overlay" id="dropdownOverlay"></div>

    <!-- ============ HEADER SECTION ============ -->
    <header class="header">
        <div class="header-left">
            <a href="../assets/index.php" class="home-logo">
            <div class="logo">
                <i class="fas fa-cube"></i> AdminPro
            </div>
            </a>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>
        </div>
        <div class="header-right">
            <div class="header-icon">
                <i class="fas fa-bell"></i>
                <span class="badge">4</span>
            </div>
            <div class="header-icon">
                <i class="fas fa-envelope"></i>
                <span class="badge">4</span>
            </div>
           
           
<div class="header-icon custom-dropdown">
    <i class="fas fa-th" id="menuButton"></i>
    <div class="dropdown-menu" id="dropdownMenu">
        <div class="dropdown-header">
            <div class="dropdown-title">Menu</div>
        </div>
        <div class="apps-grid">
            <a href="../edit_and_store_pages/edit_user_profile.php" class="app-item">
                <div class="app-icon">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <div class="app-name">Edit Profile</div>
            </a>
            <a href="#" class="app-item">
                <div class="app-icon">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div class="app-name">Settings</div>
            </a>
            <a href="../../Login/logout.php" class="app-item">
                <div class="app-icon">
                    <i class="fa-solid fa-door-open"></i>
                </div>
                <div class="app-name">Logout</div>
            </a>
        </div>  
    </div>
</div>

            <div class="header-icon">
                <a class="theme-toggle">
                    <i class="fa-regular fa-moon"></i>
                    <i class="fa-solid fa-sun"></i></a>
                </div>
                <a href="../../Landing_Page/home.php">
                <div class="header-icon">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div></a>
        </div>
        <?php
        include('notification.php');
        ?>
    </header>