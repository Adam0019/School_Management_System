<?php
include('../Config/dbcon.php');
session_start();

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validate that required fields exist
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo '<script>
        alert("Username and password are required");
        window.location.href="student_login.php";
        </script>';
        exit();
    }

    // Get and trim input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate inputs are not empty
    if (empty($username) || empty($password)) {
        echo '<script>
        alert("Username and password cannot be empty");
        window.location.href="student_login.php";
        </script>';
        exit();
    }

    try {
        // Prepare and execute query to find user
        $sql = "SELECT * FROM student_tbl WHERE username = :username";
        $smt = $pdo->prepare($sql);
        $smt->execute(['username' => $username]);
        $student = $smt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if ($student) {
            $passwordMatch = false;
            
            // Check if password is hashed (hashed passwords start with $2y$)
            if (str_starts_with($student['password'], '$2y$')) {
                // Password is hashed - use password_verify
                $passwordMatch = password_verify($password, $student['password']);
            } else {
                // Password is plain text - direct comparison (for legacy support)
                $passwordMatch = ($password === $student['password']);
                
                // If login is successful, automatically upgrade to hashed password
                if ($passwordMatch) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updateSql = "UPDATE student_tbl SET password = :password WHERE s_id = :s_id";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([
                        'password' => $hashedPassword, 
                        's_id' => $student['s_id']
                    ]);
                }
            }
            
            // If password matches
            if ($passwordMatch) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Set session variables
                $_SESSION["s_id"] = $student["s_id"];
                $_SESSION['s_name'] = $student["s_name"];
                $_SESSION['s_gender'] = $student["s_gender"];
                $_SESSION['s_img'] = $student["s_img"];
                $_SESSION['s_class'] = $student["s_class"];
                $_SESSION['userAuth'] = "Authorised";

                // Redirect to home page
                header("Location: ../Landing_Page/home.php");
                exit();
            } else {
                // Password doesn't match
                echo '<script>
                alert("Invalid username or password");
                window.location.href="student_login.php";
                </script>';
                exit();
            }
        } else {
            // User not found
            echo '<script>
            alert("Invalid username or password");
            window.location.href="student_login.php";
            </script>';
            exit();
        }
        
    } catch (PDOException $e) {
        // Log error (in production, log to file instead of displaying)
        error_log("Login error: " . $e->getMessage());
        
        echo '<script>
        alert("An error occurred. Please try again later.");
        window.location.href="student_login.php";
        </script>';
        exit();
    }
    
} else {
    // Not a POST request - redirect to login page
    header('Location: student_login.php');
    exit();
}
?>