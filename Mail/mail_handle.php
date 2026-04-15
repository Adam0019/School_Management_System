<?php
//session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Dotenv\Dotenv;

//session
$toName = $_SESSION['u_name'] ?? $_SESSION['t_name'] ?? 'Admin'; 
$toemail = $_SESSION['u_email'] ?? $_SESSION['t_email'] ?? '';
$token = $_SESSION['verification_token'] ?? '';


echo $token;

require 'vendor/autoload.php';


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->Host       = $_ENV['SMTP_HOST'];
$mail->Port       = $_ENV['SMTP_PORT'];
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->SMTPAuth   = true;
$mail->Username   = $_ENV['SMTP_USER'];
$mail->Password   = $_ENV['SMTP_PASS'];

$mail->setFrom($_ENV['SMTP_USER'], 'Jade Blaze');
$mail->addAddress($toemail, $toName);
 $mail->isHTML(true);
      
        $mail->Subject = 'Verify Your Email Address';
        $mail->Body    = "
    <html>
    <head>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                margin: 0; 
                padding: 0; 
                background-color: #f4f4f4; 
            }
            .container { 
                max-width: 600px; 
                margin: 0 auto; 
                padding: 20px; 
                background: #ffffff; 
            }
            .content { 
                background: white; 
                padding: 30px; 
                border-radius: 8px; 
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .header h1 {
                color: #333;
                font-size: 24px;
                margin: 0;
            }
            .otp-container {
                text-align: center;
                margin: 30px 0;
            }
            .otp-box {
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px 40px;
                font-size: 32px;
                font-weight: bold;
                letter-spacing: 8px;
                border-radius: 10px;
                cursor: pointer;
                user-select: all;
                transition: transform 0.2s;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }
            .otp-box:hover {
                transform: scale(1.05);
            }
            .copy-instruction {
                color: #666;
                font-size: 14px;
                margin-top: 15px;
                font-style: italic;
            }
            .info-text {
                color: #555;
                line-height: 1.6;
                margin: 20px 0;
            }
            .footer {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #eee;
                color: #999;
                font-size: 12px;
                text-align: center;
            }
            .warning {
                background: #fff3cd;
                border-left: 4px solid #ffc107;
                padding: 12px;
                margin: 20px 0;
                border-radius: 4px;
                color: #856404;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='content'>
                <div class='header'>
                    <h1>Email Verification</h1>
                </div>
                
                <p class='info-text'>Hi <strong>{$toName}</strong>,</p>
                <p class='info-text'>Thanks for registering with Jade Blaze! To complete your registration, please verify your email address using the OTP below:</p>
                
                <div class='otp-container'>
                    <div class='otp-box' onclick='selectOTP(this)' title='Click to select and copy'>
                        {$token}
                    </div>
                    <p class='copy-instruction'>👆 Click on the code above to select it, then copy it (Ctrl+C or Cmd+C)</p>
                </div>
                
                <p class='info-text'>Enter this code on the verification page to activate your account.</p>
                
                <div class='warning'>
                    <strong>⚠️ Security Note:</strong> This code will expire in 15 minutes. If you didn't create this account, please ignore this email.
                </div>
                
                <div class='footer'>
                    <p>This is an automated email from Jade Blaze. Please do not reply to this message.</p>
                </div>
            </div>
        </div>
        
        <script>
            function selectOTP(element) {
                if (window.getSelection && document.createRange) {
                    const selection = window.getSelection();
                    const range = document.createRange();
                    range.selectNodeContents(element);
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            }
        </script>
    </body>
    </html>";

$mail->AltBody = "Hi {$toName},\n\nThanks for registering with Jade Blaze!\n\nYour verification code is: {$token}\n\nPlease enter this code on the verification page to activate your account.\n\nThis code will expire in 15 minutes.\n\nIf you didn't create this account, please ignore this email.\n\n---\nJade Blaze Team";
        
try {
    $mail->send();
  
     echo '<script>
        alert("A verification email has been sent to your email address.");
        window.location.href="../Mail/Auth/otp_verify.php";
        </script>';
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}