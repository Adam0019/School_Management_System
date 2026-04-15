<?php
session_start();
include('Config/dbcon.php');

if(isset($_POST['submit'])){
    if (!isset($_POST['u_name']) || !isset($_POST['u_email']) || !isset($_POST['password'])) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }
    
    $u_name = trim($_POST['u_name']);
    $u_email = trim($_POST['u_email']);
    $password = $_POST['password'];
    $username = trim($_POST['u_email']);

    // Basic server-side validation
    if(strlen($u_name) < 2) {
        exit("Name must be at least 2 characters.");
    }
    if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
        exit("Invalid email.");
    }
    if (strlen($password) < 8) {
        exit("Password must be at least 8 characters.");
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM user_tbl WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    if ($stmt->fetch()){
        echo '<script>
        alert("Username already exists");
        window.location.href="login.php";
        </script>';
        exit;
    } else {
        // Hash password (IMPORTANT!)
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Generate 6-digit OTP
        $verification_token = random_int(100000, 999999);

        $u_key= random_int(100000, 999999);


        $sql = "INSERT INTO user_tbl (u_name, u_email, username, password, u_key) 
                VALUES (:u_name, :u_email, :username, :password, :u_key)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':u_name', $u_name);
            $stmt->bindParam(':u_email', $u_email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':u_key', $u_key);
           
            
            if($stmt->execute()){
                // Get the newly created user ID
                $new_user_id = $pdo->lastInsertId();

                 $sql1 = "INSERT INTO otp_tbl (verification_token, u_id, created_at) 
                VALUES (:verification_token, :u_id, NOW())";
         try {
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->bindParam(':verification_token', $verification_token);
            $stmt1->bindParam(':u_id', $new_user_id);

            if($stmt1->execute()){
                $_SESSION['verification_token'] = $verification_token;
                $_SESSION['u_id'] = $new_user_id;
                $_SESSION['u_name'] = $u_name;
                $_SESSION['u_email'] = $u_email;
                $_SESSION['u_key'] = $u_key;
                include('../Mail/mail_handle.php');
                header("Location: ../Mail/verify_otp.php");
                exit();
            }
        } catch (Exception $e) {
            echo "Error generating OTP: " . $e->getMessage();
        }        
     }
        } catch (PDOException $e) {
           
            echo '<script>
            alert("Registration failed! ' . $e->getMessage() . '");
            window.location.href="login.php";
            </script>';
        }
    }
}
?>