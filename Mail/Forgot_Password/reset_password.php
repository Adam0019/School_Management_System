<?php
session_start();
include('../../Config/dbcon.php');
// Check if user is authorized to reset password
if (!isset($_SESSION['username'])) {
    header('Location: forgot_password.php');
    exit;
}
$login_type = isset($_GET['action']) ? $_GET['action'] : 'Admin'; // Default to Admin if not specified
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
    <link rel="stylesheet" href="../assets/CSS/forgot_pasword.css" />
    <title>Reset Password</title>
  </head>

  <body>
    <div class="container" id="container">
      
      <div class="form-container sign-in">
        <form action="update_password.php" method="POST">
          <h1>Enter your new Password</h1>
          <div class="input-group">
            <input type="password"  id="new_password" name="new_password" class="input" placeholder="Password" required />
           <input type="password"  id="confirm_password" name="confirm_password" class="input" placeholder=" Confirm Password" required />
            <!-- <p class="input-hint">Enter your registered username</p> -->
          </div>
          <div class="input-group" style="display: none;">
            <select name="type" id="type" class="input" required>
              <option value="<?php echo  $login_type; ?>" selected><?php echo  $login_type; ?></option>
            </select>
          </div>
          <?php
          if( $login_type === 'Teacher'){
            echo '<a href="../../Login/teacher_login.php">Remembered Your Password? Click to go back</a>';
          } else {
            echo '<a href="../../Login/login.php">Remembered Your Password? Click to go back</a>';
          }?>
          <button type="submit" class="button" value="submit" name="submit">Verfiy</button>
        </form>
      </div>
    </div>

  </body>
</html>