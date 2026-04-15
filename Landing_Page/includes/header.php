<?php
include('./../Config/dbcon.php');
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Check if user is logged in
$isLoggedIn = isset($_SESSION['u_id']) || isset($_SESSION['s_id']) || isset($_SESSION['t_id']);

// Get user profile picture - use ternary operator or null coalescing
$userProfilePic = $_SESSION['u_img'] ?? $_SESSION['s_img'] ?? $_SESSION['t_img'] ?? '../../Admin-Dashboard/assets/image/default.jpg';

// Get user name - use ternary operator or null coalescing
$userName = $_SESSION['u_name'] ?? $_SESSION['s_name'] ?? $_SESSION['t_name'] ?? 'Guest';

?>
<!DOCTYPE html>
<html>
	 <title>Home</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    />
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Fonts-->
    <link
      rel="stylesheet"
      type="text/css"
      href="./assets/fonts/fontawesome/font-awesome.min.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="./assets/fonts/themify-icons/themify-icons.css"
    />
    <!-- Vendors-->
    <link
      rel="stylesheet"
      type="text/css"
      href="./assets/vendors/bootstrap4/bootstrap-grid.min.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="./assets/vendors/magnific-popup/magnific-popup.min.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="./assets/vendors/owl.carousel/owl.carousel.css"
    />
    <!-- <link
      rel="stylesheet"
      type="text/css"
      href="./assets/vendors/_jquery/jquery.min.css"
    /> -->
    <!-- <link rel="stylesheet" type="text/css" href="../assets/vendors/bootstrap4/bootstrap-grid.min.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="../assets/vendors/bootstrap4/bootstrap-grid.min.css"> -->
    <!-- App & fonts-->
    <link
      rel="stylesheet"
      type="text/css"
      href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i&amp;amp;subset=latin-ext"
    />
    <link rel="stylesheet" type="text/css" href="./assets/css/main.css" />
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
  </head>
	
	<body>
		<div class="page-wrap">
			
			<!-- header -->
			<header class="header">
				<div class="container">
					<div class="header__logo"><a href="home.php"><img src="./assets/img/logo.png" alt=""/></a></div>
					<div class="header__toogleGroup">
						<div class="header__chooseLanguage">
							<!-- dropdown -->
									
							<div class="dropdown" data-init="dropdown"><a class="dropdown__toggle" href="javascript:void(0)"><p class="profile-dropdown__name"><?php 
							if ($isLoggedIn) { echo htmlspecialchars($userName); ?></p></a>
											<div class="dropdown__content" data-position="right">
												<ul class="list-style-none">
													<li><a href="./../Admin-Dashboard/assets/index.php"><i class="fa fa-user"></i> My Profile</a></li>
													<li><a href="./../Login/logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
												</ul>
											</div>
											
							<?php } else{ ?> Login/Register</p></a>
											<div class="dropdown__content" data-position="right">
												<ul class="list-style-none">
													<li><a href="./../Login/student_login.php"><i class="fa fa-sign-in"></i> As Student</a></li>
													<li><a href="./../Login/teacher_login.php"><i class="fa fa-sign-in"></i> As Teacher</a></li>
												</ul>
											</div>
								<!-- Login Button -->
							<?php } ?>
										</div><!-- End / dropdown -->
										
						</div>
						<div class="search-form">
							<div class="search-form__toggle"><i class="ti-search"></i></div>
							<div class="search-form__form">
												
												<!-- form-search -->
												<div class="form-search">
													<form>
														<input class="form-control" type="text" placeholder="Hit enter to search or ESC to close"/>
													</form>
												</div><!-- End / form-search -->
												
							</div>
						</div>
						
						
					</div>
					
					<!-- consult-nav -->
					<nav class="consult-nav">
						
						<!-- consult-menu -->
						<ul class="consult-menu">
							<li class="current-menu-item"><a href="home.php">Home</a>
							</li>
							<li><a href="about_page.php">about</a>
							</li>
							<li class="menu-item-has-children"><a href="#">page</a>
								<ul class="sub-menu">
									<li><a href="comming-soon.html">Comming Soon</a>
									</li>
									<li><a href="404.html">404</a>
									</li>
									<li><a href="typography.html">Typography</a>
									</li>
								</ul>
							</li>
							<li><a href="service.html">services</a>
							</li>
							<li class="menu-item-has-children"><a href="project.html">project</a>
								<ul class="sub-menu">
									<li><a href="project-detail.html">Project detail</a>
									</li>
								</ul>
							</li>
							<li class="menu-item-has-children"><a href="blog.html">blog</a>
								<ul class="sub-menu">
									<li><a href="blog-detail.html">Blog detail</a>
									</li>
								</ul>
							</li>
							<li><a href="contact_page.php">contact</a>
							</li>
						</ul><!-- consult-menu -->
						
						<div class="navbar-toggle"><span></span><span></span><span></span></div>
					</nav><!-- End / consult-nav -->
					
				</div>
			</header><!-- End / header -->