<?php
include('../Config/dbcon.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="assets/css/style.css" />
    <title>Login Page | Student</title>
  </head>

  <body>
    <div class="container" id="container">
      <div class="form-container sign-in">
        <form action="login_check.php?action=Student" method="POST">
          <h1>Sign In</h1>
          <div class="social-icons">
            <a href="#" class="icon"
              ><i class="fa-brands fa-google-plus-g"></i
            ></a>
            <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
            <a href="#" class="icon"
              ><i class="fa-brands fa-linkedin-in"></i
            ></a>
          </div>
          <span>or use your username & password</span>
          
          <div class="input-group">
            <input type="text"  id="username" name="username" class="input" placeholder="Username" required />
            <!-- <p class="input-hint">Enter your registered username</p> -->
          </div>
          
          <div class="input-group">
            <input type="password" id="password" data-type="password" name="password" class="input" placeholder="Password" required  />
            <!-- <p class="input-hint">Your secure password</p> -->
          </div>
          
          <!-- <a href="../Mail/Forgot_Password/forgot_password.php">Forget Your Password?</a> -->
          <button type="submit" class="button" value="submit" name="submit">Login</button>
        </form>
      </div>
      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-right">
            <h3>Hello, Student! Welcome Back!</h3>
            <p>Enter your login details to use all of site features</p>
             <a href="../Landing_Page/home.php"> <-- Go back?</a>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/JS/script.js"></script>
    <script src="assets/JS/form_error.js"></script>
  </body>
</html>