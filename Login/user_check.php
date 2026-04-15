<?php
include('../Config/dbcon.php');
session_start();
if (isset($_POST['submit'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $sql="SELECT * FROM user_tbl WHERE username=:username";
    $smt=$pdo -> prepare($sql);
    $smt-> execute(['username'=>$username]);
    $user = $smt->fetch(PDO::FETCH_ASSOC);
   echo $user['username'];

     if ($user && password_verify($password, $user['password'])){

        // Generate 6-digit OTP
        $verification_token = random_int(100000, 999999);
        $t_id = 0; // Not used for user login
        
        $sql1 = "INSERT INTO otp_tbl (verification_token, u_id, t_id, created_at) 
                VALUES (:verification_token, :u_id, :t_id, NOW())";
        try {
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->bindParam(':verification_token', $verification_token);
            $stmt1->bindParam(':u_id', $user['u_id']);
            $stmt1->bindParam(':t_id', $user['t_id']);
            
            if($stmt1->execute()){
                $_SESSION['verification_token'] = $verification_token;
                $_SESSION["u_id"] = $user["u_id"];
                $_SESSION['u_name'] = $user["u_name"];
                $_SESSION['u_email'] = $username;
                
                include("../Mail/mail_handle.php");
                echo "OTP has been sent to your email.";
                exit();
            }
        } catch (Exception $e) {
            echo "Error updating verification token: " . $e->getMessage();
        }
    }
    else{
        echo '<script>
        alert("Invalid username or password");
        window.location.href="login.php";
        </script>';
    }
}

?>