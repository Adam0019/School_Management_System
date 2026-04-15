<?php
require_once '../../Config/dbcon.php';

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
    <link rel="stylesheet" href="../assets/css/otp_style.css" />
    <title>Verify using OTP</title>
  </head>

 <body>
    <div class="container">
        <h2>Verify OTP</h2>
        <p class="info-text">Enter the 6-digit code sent to your Email<br><strong></strong></p>
        
        <form method="POST" action="otp_check.php">
            <div class="form-group">
                <label for="otp">OTP Code:</label>
                <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" required placeholder="000000">
            </div>
            
            <button type="submit" name="verify" class="btn">Verify OTP</button>
        </form>
        
        <div class="resend-link">
            <p>Didn't receive the code? <a href="resend_otp.php">Resend OTP</a></p>
        </div>
    </div>
</body>
</html>