<?php
session_start();
include('../../Config/dbcon.php');

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $key = trim($_POST['key']);
    $type = trim($_POST['type']);
    
    if ($type === 'Teacher') {
           $sql = "SELECT * FROM teacher_tbl WHERE username=:username";
            $smt = $pdo->prepare($sql);
            $smt->execute(['username' => $username]);
            $user_data = $smt->fetch(PDO::FETCH_ASSOC);
            
            if ($user_data && $key == $user_data['t_key']) {
                $new_key = random_int(100000, 999999);
                $update_sql = "UPDATE teacher_tbl SET t_key = :new_key WHERE username = :username";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->bindParam(':new_key', $new_key);
                $update_stmt->bindParam(':username', $username);
                
                if ($update_stmt->execute()) {
                    $_SESSION['username'] = $username;
                    $_SESSION['new_key'] = $new_key;
                   
                    echo '<script>
                    alert("Verification successful! You can now reset your password.\\n\\nHere is your new key: ' . $new_key . '\\n\\nMake sure to store it safely for future use!");
                    window.location.href = "reset_password.php?action=Teacher";
                    </script>';
                    exit;
                } else {

                    echo '<script>
                    alert("Failed to update key. Please try again.");
                    window.location.href = "forgot_password.php?action=Teacher";
                    </script>';
                    exit;
                }
            } else {
                echo '<script>
                alert("Invalid username or key");
                window.location.href = "forgot_password.php?action=Teacher";
                </script>';
                exit;
            }
    }
    elseif($type === 'Admin'){
           $sql = "SELECT * FROM user_tbl WHERE username=:username";
            $smt = $pdo->prepare($sql);
            $smt->execute(['username' => $username]);
            $user_data = $smt->fetch(PDO::FETCH_ASSOC);
            
            if ($user_data && $key == $user_data['u_key']) {
                $new_key = random_int(100000, 999999);
                $update_sql = "UPDATE user_tbl SET u_key = :new_key WHERE username = :username";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->bindParam(':new_key', $new_key);
                $update_stmt->bindParam(':username', $username);
                
                if ($update_stmt->execute()) {
                    $_SESSION['username'] = $username;
                    $_SESSION['new_key'] = $new_key;
                   
                    echo '<script>
                    alert("Verification successful! You can now reset your password.\\n\\nHere is your new key: ' . $new_key . '\\n\\nMake sure to store it safely for future use!");
                    window.location.href = "reset_password.php?action=User";
                    </script>';
                    exit;
                } else {
                    
                    echo '<script>
                    alert("Failed to update key. Please try again.");
                    window.location.href = "forgot_password.php?action=User";
                    </script>';
                    exit;
                }
            } else {
                
                echo '<script>
                alert("Invalid username or key");
                window.location.href = "forgot_password.php?action=User";
                </script>';
                exit;
            
            }
    }


}else {
    
    header('Location: forgot_password.php');
    exit;
}
?>