<?php
session_start();
include('../../Config/dbcon.php');

// Check if user is authorized
if (!isset($_SESSION['username'])) {
    header('Location: forgot_password.php');
    exit;
}

if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $type = trim($_POST['type']);
    $new_password = trim($_POST["new_password"] ?? "");
    $confirm_password = trim($_POST["confirm_password"] ?? "");
    
    // Validate password length
    if (strlen($new_password) < 8) {
       
        echo '<script>
        alert("Password must be at least 8 characters long.");
        window.location.href="reset_password.php?action=' . $type . '";
        </script>';
        exit;
    }
    
    // Check if passwords match
    if ($new_password !== $confirm_password) {
       
        echo '<script>
        alert("Passwords do not match.");
        window.location.href="reset_password.php?action=' . $type . '";
        </script>';
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    if($type === 'Teacher'){
        $sql = "SELECT * FROM teacher_tbl WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username]);
            $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($teacher) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update password in database
                $update_sql = "UPDATE teacher_tbl SET password = :password WHERE username = :username";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $update_stmt->bindParam(':username', $username, PDO::PARAM_STR);
                
                if ($update_stmt->execute()) {
                    // Clear session and redirect
                    session_destroy();
                    echo '<script>
                    alert("Password reset successful. You can now log in with your new password.");
                    window.location.href="../../Login/teacher_login.php";
                    </script>';
                    exit;
                } else {
                    
                    echo '<script>
                    alert("Failed to update password. Please try again.");
                    window.location.href="reset_password.php?action=' . $type . '";
                    </script>';
                    exit;
                }
            } else {
                // User not found - clear session and redirect
                session_destroy();
                
                echo '<script>
                alert("Invalid session. Please start the password reset process again.");
                window.location.href="forgot_password.php?action=' . $type . '";
                </script>';
                exit;
            }
    } elseif($type === 'Admin'){
        $sql = "SELECT * FROM user_tbl WHERE username = :username;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update password in database
                $update_sql = "UPDATE user_tbl SET password = :password WHERE username = :username";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $update_stmt->bindParam(':username', $username, PDO::PARAM_STR);
                
                if ($update_stmt->execute()) {
                    // Clear session and redirect
                    session_destroy();
                    echo '<script>
                    alert("Password reset successful. You can now log in with your new password.");
                    window.location.href="../../Login/login.php";
                    </script>';
                    exit;
                } else {
                    
                    echo '<script>
                    alert("Failed to update password. Please try again.");
                    window.location.href="reset_password.php?action=' . $type . '";
                    </script>';
                    exit;
                }
            } else {
                // User not found - clear session and redirect
                session_destroy();
                
                echo '<script>
                alert("Invalid session. Please start the password reset process again.");
                window.location.href="forgot_password.php?action=' . $type . '";
                </script>';
                exit;
            }
    
}} else {
    header('Location: reset_password.php');
    exit;
}
?>