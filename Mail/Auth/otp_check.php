<?php
session_start();
require_once '../../Config/dbcon.php';

// Determine user type and validate session
$user_type = null;
$user_id = null;
$user_name = null;

// Check if user has a verification token in session

if (isset($_SESSION['t_id']) && isset($_SESSION['verification_token'])) {
    $user_type = 'teacher';
    $user_id = $_SESSION['t_id'];
    $user_name = $_SESSION['t_name'];
} elseif (isset($_SESSION['u_id']) && isset($_SESSION['verification_token'])) {
    $user_type = 'admin';
    $user_id = $_SESSION['u_id'];
    $user_name = $_SESSION['u_name'];
} else {
    echo '<script>
    alert("Invalid access. Please login first.");
    window.location.href="../../Login/login.php";
    </script>';
    exit();
}

if (isset($_POST['verify'])) {
    $entered_otp = trim($_POST['otp']);
    $session_token = $_SESSION['verification_token'];
    $u_key = isset($_SESSION['u_key']) ? $_SESSION['u_key'] : null;

    // Build SQL query based on user type
    if ($user_type == 'teacher') {
        $sql = "SELECT * FROM otp_tbl 
                WHERE verification_token = :token 
                AND t_id = :user_id 
                AND otp_sts = 0 
                ORDER BY created_at DESC 
                LIMIT 1";
    } else {
        $sql = "SELECT * FROM otp_tbl 
                WHERE verification_token = :token 
                AND u_id = :user_id 
                AND otp_sts = 0 
                ORDER BY created_at DESC 
                LIMIT 1";
    }
    
      try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $session_token);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $otp_record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otp_record) {
            // Check if OTP has expired (10 minutes validity)
            $created_time = strtotime($otp_record['created_at']);
            $current_time = time();
            $time_diff = ($current_time - $created_time) / 60; // difference in minutes

            if ($time_diff > 1) {
                echo '<script>
                alert("OTP has expired. Please request a new one.");
                window.location.href="../../Login/login.php';
                if ($user_type == 'teacher') {
                    echo '?action=Teacher';
                }
                echo '");
                </script>';
                exit();
            }
 
            // Verify OTP
            if ($entered_otp == $session_token) {
                // Mark OTP as used
                $update_sql = "UPDATE otp_tbl SET otp_sts = 1 WHERE otp_id = :otp_id";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->bindParam(':otp_id', $otp_record['otp_id']);
                $update_stmt->execute();

                // Set user as authenticated
                $_SESSION['userAuth'] = "Authorised";

                // Clear verification token from session
                unset($_SESSION['verification_token']);

                // Prepare success message and redirect based on user type
                if ($user_type == 'teacher') {
                    $message = "OTP verified successfully! Welcome back, " . $user_name . "!";
                    $redirect_url = "../../Landing_Page/home.php";
                } else {
                    // Regular user
                    if ($u_key != null) {
                        $message = "OTP verified successfully! User registered successfully! \\n\\nHere is your key: " . $u_key . "\\n\\nMake sure to store it safely!";
                        unset($_SESSION['u_key']);
                    } else {
                        $message = "OTP verified successfully! Welcome back, " . $user_name . "!";
                    }
                    $redirect_url = "../../Landing_Page/home.php";
                }
                
                // window.location.href="../../Admin-Dashboard/assets/index.php";
          
                echo '<script>
                alert("' . $message . '");
                window.location.href="' . $redirect_url . '";
                </script>';
                exit();
            } else {
                echo '<script>
                alert("Invalid OTP. Please try again.");
                window.location.href="otp_verify.php";
                </script>';
                exit();
            }
        } else {
            echo '<script>
            alert("OTP not found or already used.");
            window.location.href="../../Login/teacher_login.php';
            if ($user_type == 'teacher') {
                echo '?action=Teacher';
            }
            echo '");
            </script>';
            exit();
        }
    } catch (Exception $e) {
        echo '<script>
        alert("Error verifying OTP. Please try again.");
        window.location.href="otp_verify.php";
        </script>';
        exit();
    }
}

// If no POST request, show the form
include('otp_verify.php');
?>