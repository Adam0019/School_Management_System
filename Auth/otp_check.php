<?php
session_start();
include('../Config/dbcon.php');
// Check if user has a verification token in session
if (!isset($_SESSION['verification_token']) || !isset($_SESSION['u_id'])) {
    echo '<script>
    alert("Invalid access. Please login first.");
    window.location.href="../login.php";
    </script>';
    exit;
}

if (isset($_POST['verify'])) {
    $entered_otp = trim($_POST['otp']);
    $session_token = $_SESSION['verification_token'];
    $u_id = $_SESSION['u_id'];

    // Fetch OTP from database
    $sql = "SELECT * FROM otp_tbl 
            WHERE verification_token = :token 
            AND u_id = :u_id 
            AND otp_sts = 0 
            ORDER BY created_at DESC 
            LIMIT 1";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $session_token);
        $stmt->bindParam(':u_id', $u_id);
        $stmt->execute();
        $otp_record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otp_record) {
            // Check if OTP has expired (10 minutes validity)
            $created_time = strtotime($otp_record['created_at']);
            $current_time = time();
            $time_diff = ($current_time - $created_time) / 60; // difference in minutes

            if ($time_diff > 10) {
                echo '<script>
                alert("OTP has expired. Please request a new one.");
                window.location.href="../login.php";
                </script>';
                exit;
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

                echo '<script>
                alert("OTP verified successfully!");
                window.location.href="../Dashboard/index.php";
                </script>';
                exit;
            } else {
                echo '<script>
                alert("Invalid OTP. Please try again.");
                window.location.href="otp_verify.php";
                </script>';
                exit;
            }
        } else {
            echo '<script>
            alert("OTP not found or already used.");
            window.location.href="../login.php";
            </script>';
            exit;
        }
    } catch (Exception $e) {
        echo "Error verifying OTP: " . $e->getMessage();
    }
}
// If no POST request, show the form
include('otp_verify.php');
?>