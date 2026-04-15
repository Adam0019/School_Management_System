<?php
session_start();
include('../../Config/dbcon.php');

if (isset($_POST['submit'])){
$username = trim($_POST['username']);
    $key = trim($_POST['key']);
    $type = isset($_SESSION['type']) ? $_SESSION['type'] : 'Admin'; // Default to Admin if not set
    $sql="SELECT * FROM teacher_tbl WHERE username=:username";
    $smt=$pdo -> prepare($sql);
    $smt-> execute(['username'=>$username]);
    $teacher = $smt->fetch(PDO::FETCH_ASSOC);
//    echo $teacher['username'];
//    echo $type;

     if ($teacher && $key == $teacher['t_key']){
            $new_key= random_int(100000, 999999);
            $update_sql = "UPDATE teacher_tbl SET t_key = :new_key WHERE username = :username";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->bindParam(':new_key', $new_key);
            $update_stmt->bindParam(':username', $username);
            if($update_stmt->execute()){
                $_SESSION['username'] = $username;
                 $message = "Verification successful! You can now reset your password. & \\n\\nHere is your new key: " . $new_key . "\\n\\nMake sure to store it safely for future use!";
                echo '<script>
                alert("' . $message . '");
                window.location.href="reset_password.php";
                </script>';
                exit;
              
            }else {
            echo '<script>
            alert("Failed to update key. Please try again.");
            window.location.href="forgot_password.php";
            </script>';
            exit;
        }
        } else {
        echo '<script>
        alert("Invalid email or key");
        window.location.href="forgot_password.php";
        </script>';
        exit;
    }
    }
    else{
        header('Location: forgot_password.php');
    exit;
    }


?>