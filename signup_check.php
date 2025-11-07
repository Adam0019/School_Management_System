<?php

include('Config/dbcon.php');
// header("Content-Type: application/json");

if(isset($_POST['submit'])){
    if (!isset($_POST['username']) || !isset($_POST['u_email']) || !isset($_POST['password']) ) {   //|| !isset($_POST['role'])
        echo json_encode(["error" => "Missing required fields"]);
        exit;}
        $username = $_POST['username'];
        $email = $_POST['u_email'];
        $password = $_POST['password'];
        // $role = $_POST['role'];
        echo $username;

    $stmt = $pdo->prepare("SELECT * FROM user_tbl WHERE username =:username");
    $stmt->execute([$username]);
    if ($stmt-> fetch()){
        echo '<script>
        alert("Username already exists");
        window.location.href="login.php";
        </script>';
        exit;
    }
else{
    $sql = "INSERT INTO user_tbl ( u_email, username, password) VALUES ( :email, :username, :password)";

    try{
    $stmt = $pdo->prepare($sql);
    // $stmt-> bindParam(':name', $name);
    $stmt-> bindParam(':email', $email);
    // $stmt-> bindParam(':phone', $phone);
    $stmt-> bindParam(':username', $username);
    $stmt-> bindParam(':password', $password);
    // $stmt-> bindParam(':role', $role);
    if($stmt-> execute()){
        $_SESSION['userAuth'] = "Authorised";}
     $_SESSION['toastr']=['
            type' => 'success', // success, error, info, warning
            'message' => 'User created successfully!'
    ];

    // if(isset($_POST['fromUser'])){
    //     // header('Location: ../User/user.php');
    //     // exit();
    //     echo json_encode(["success" => "User created successfully"]);
    //     exit();
    // }

    header('Location:Dashboard/index.php');
    exit();
    }catch (PDOException $e){
        $_SESSION['toastr']=[
            'type' => 'danger',
            'message' => 'User is not created! Error:' . $e->getMessage()
        ];
    }
    }}

    // if ($stmt->execute()) {
    //     echo '<script>
    //     alert("Registration successful!");
    //     window.location.href="index.php";
    //     </script>';
    // } else {
    //     echo '<script>
    //     alert("Registration failed!");
    //     window.location.href="signup.php";
    //     </script>';
    // }




?>