<?php
include('../../Config/dbcon.php');
$login_type = isset($_GET['action']) ? $_GET['action'] : 'Admin'; // Default to Admin if not specified

// echo $login_type;
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
    <link rel="stylesheet" href="../assets/css/forgot_pasword.css" />
    <title>Forgot Password</title>
  </head>

  <body>
    <div class="container" id="container">
      
      <div class="form-container sign-in">
        <form action="verify_key.php" method="POST">
          <h1>Enter your Email & Key</h1>
          <div class="input-group">
            <input type="email"  id="username" name="username" class="input" placeholder="Username" required />
            </div>
             <div class="input-group">
            <input type="text"  id="key" name="key" class="input" placeholder="Key" required />
            <!-- <p class="input-hint">Enter your registered username</p> -->
          </div>
          <div class="input-group" style="display: none;">
            <select name="type" id="type" class="input" required>
              <option value="<?php echo $login_type; ?>" selected><?php echo $login_type; ?></option>
            </select>
          </div>
          <?php 
          if($login_type === 'Teacher'){
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