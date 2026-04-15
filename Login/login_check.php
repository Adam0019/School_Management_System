<?php
session_start();
include('../Config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login_type = isset($_GET['action']) ? $_GET['action'] : 'Admin'; // Default to Admin if not specified
    
    // Teacher Login
    switch($login_type){
        case 'Teacher':
          $sql="SELECT * FROM teacher_tbl WHERE username=:username";
          $smt= $pdo->prepare($sql);
          $smt->execute(['username'=>$username]);
          $teacher=$smt->fetch(PDO::FETCH_ASSOC);

          if($teacher && password_verify($password, $teacher['password'])){
            // Before inserting new OTP, delete old ones for this user
            $cleanup = "DELETE FROM otp_tbl WHERE t_id = :t_id";
            $pdo->prepare($cleanup)->execute(['t_id' => $teacher['t_id']]);

            //generate 6-digit OTP
            $verification_token = random_int(100000, 999999);
            $u_id = null; // Not used for teacher login
            
            $sql1 = "INSERT INTO otp_tbl (verification_token, t_id, u_id, created_at) VALUES (:verification_token, :t_id, :u_id, NOW())";
          
          try{
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->bindParam(':verification_token', $verification_token);
            $stmt1->bindParam(':t_id', $teacher['t_id']);
            $stmt1->bindParam(':u_id', $u_id);

            if($stmt1->execute()){
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                $_SESSION['verification_token'] = $verification_token;
                $_SESSION["t_id"] = $teacher["t_id"];
                $_SESSION['t_name'] = $teacher["t_name"];
                $_SESSION['t_gender'] = $teacher["t_gender"];
                $_SESSION['t_img'] = $teacher["t_img"];
                $_SESSION['t_email'] = $username;
                $_SESSION['type']= $login_type;
                
                   include("../Mail/mail_handle.php");
                   echo "OTP has been sent to your email.";
                   header("Location: otp_verify.php");
                    exit();
          }
        } catch (Exception $e) {
            echo "Error updating verification token: " . $e->getMessage();
        }
    } else {
        echo '<script>
        alert("Invalid username or password");
        window.location.href="teacher_login.php";
        </script>';
        exit();
    }
    break;
    case 'Student':
        $sql="SELECT * FROM student_tbl WHERE username=:username";
        $smt= $pdo->prepare($sql);
        $smt->execute(['username'=>$username]);
        $student=$smt->fetch(PDO::FETCH_ASSOC);

        if($student && password_verify($password, $student['password'])){
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION["s_id"] = $student["s_id"];
            $_SESSION['s_name'] = $student["s_name"];
            $_SESSION['s_gender'] = $student["s_gender"];
            $_SESSION['s_img'] = $student["s_img"];
            $_SESSION['s_class'] = $student["s_class"];
            $_SESSION['type']= $login_type;
             $_SESSION['userAuth'] = "Authorised";

            // include("../../Landing_Page/home.php");
            header("Location: ../Landing_Page/home.php");
            exit();
        } else {
    
    echo '<script>
    alert("Invalid username or password");  // Changed from "Error processing login"
    window.location.href="student_login.php";
    </script>';
            exit();
        }
        break;
    case 'Admin':
    $sql = "SELECT * FROM user_tbl WHERE username=:username";
    $smt = $pdo->prepare($sql);
    $smt->execute(['username' => $username]);
    $user = $smt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Before inserting new OTP, delete old ones for this user
        $cleanup = "DELETE FROM otp_tbl WHERE u_id = :u_id";
        $pdo->prepare($cleanup)->execute(['u_id' => $user['u_id']]);
        
        // Generate 6-digit OTP
        $verification_token = random_int(100000, 999999);
        $t_id = null; // Not used for user login

        $sql1 = "INSERT INTO otp_tbl (verification_token, u_id, t_id, created_at) 
                VALUES (:verification_token, :u_id, :t_id, NOW())";
        try {
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->bindParam(':verification_token', $verification_token);
            $stmt1->bindParam(':u_id', $user['u_id']);
            $stmt1->bindParam(':t_id', $t_id);

            if ($stmt1->execute()) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                $_SESSION['verification_token'] = $verification_token;
                $_SESSION["u_id"] = $user["u_id"];
                $_SESSION['u_name'] = $user["u_name"];
                $_SESSION['u_email'] = $username;
                $_SESSION['u_gender'] = $user["u_gender"];
                $_SESSION['u_img'] = $user["u_img"];
                $_SESSION['role'] = $user["role"];
                $_SESSION['type'] = $login_type;
                
                include("../Mail/mail_handle.php");
                header("Location: otp_verify.php");
                exit();
            }
        } catch (Exception $e) {
            echo "Error updating verification token: " . $e->getMessage();
        }
    } else {
        echo '<script>
        alert("Invalid username or password");
        window.location.href="login.php";
        </script>';
        exit();
    }
    break;
    default:
        echo '<script>
        alert("Invalid login type.");
        window.location.href="login.php";
        </script>';
        exit();
    }}else {
    // Direct access without form submission
    header("Location: login.php");
    exit();
}
    ?>