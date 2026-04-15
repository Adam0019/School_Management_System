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
    <title>Login Page | Caged coder</title>
  </head>

  <body>
    <div class="container" id="container">
      <div class="form-container sign-up">
        <form action="signup_check.php" method="POST">
          <h1>Create Account</h1>
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
          <span>or use your email for registeration</span>
          
          <div class="input-group">
            <input type="text" id="u_name"  name="u_name" class="input" placeholder="Name"  required/>
            <p class="input-error" data-error-for="u_name"></p>
          </div>
          
          <div class="input-group">
            <input type="email" id="u_email"  name="u_email" class="input" placeholder="Email" required/>
            <p class="input-error" data-error-for="u_email"></p>
          </div>
          
          <div class="input-group">
            <input type="password" id="password" data-type="password" name="password" class="input" placeholder="Password" required/>
            <p class="input-error" data-error-for="password"></p>
            <p class="input-hint">Min 8 char, include letters & numbers</p>
          </div>
          
          <button type="submit" class="button" value="Signup" name="submit">Sign Up</button>
        </form>
      </div>
      <div class="form-container sign-in">
        <form action="login_check.php?action=Admin" method="POST">
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
          
          <a href="../Mail/Forgot_Password/forgot_password.php?action=Admin">Forget Your Password?</a>
          <button type="submit" class="button" value="Login" name="submit">Login</button>
        </form>
      </div>
      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Welcome Back!</h1>
            <p>Enter your personal details to use all of site features</p>
            <button class="hidden" id="login">Sign In</button>
          </div>
          <div class="toggle-panel toggle-right">
            <h1>Hello, Friend!</h1>
            <p>
              Register with your personal details to use all of site features
            </p>
            <button class="hidden" id="register">Sign Up</button>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/JS/script.js"></script>
    <script src="assets/JS/form_error.js"></script>
  </body>
</html>