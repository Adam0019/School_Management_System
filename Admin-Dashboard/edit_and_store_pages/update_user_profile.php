<?php
session_start();
include('../../Config/dbcon.php'); // Or wherever your PDO connection is

if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL){
    header('Location: ../../Login/logout.php');
    exit();
}


if(isset($_POST['submit'])){
    try{
        $u_id = $_POST['u_id'];
        $u_name = $_POST['u_name'];
        $u_gender = $_POST['u_gender'];
        $u_email = $_POST['u_email'];
        $u_phone = $_POST['u_phone'];
        $u_address = $_POST['u_address'];
        $u_about = $_POST['u_about'];

        // First, get current user data
        $SELECT = "SELECT * FROM user_tbl WHERE u_id=?";
        $stmt = $pdo->prepare($SELECT);
        $stmt->execute([$u_id]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Handle image upload
        $imageName = $currentUser['u_img']; // Keep existing image by default
        $imageChanged = false;
        
        if(isset($_FILES['u_img']) && $_FILES['u_img']['error'] == 0){
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['u_img']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            
            if(in_array(strtolower($filetype), $allowed)){
                // Generate unique filename
                $imageName = time() . '_' . $filename;
                $upload_path = '../assets/image/' . $imageName;
                
                // Move uploaded file
                if(move_uploaded_file($_FILES['u_img']['tmp_name'], $upload_path)){
                    // Delete old image if it exists and isn't a default
                    if($currentUser['u_img'] && !in_array($currentUser['u_img'], ['male_default.jpeg', 'female_default.jpeg', 'default.jpg'])){
                        $old_file = '../assets/image/' . $currentUser['u_img'];
                        if(file_exists($old_file)){
                            unlink($old_file);
                        }
                    }
                    $imageChanged = true;
                } else {
                    $_SESSION['error'] = 'Failed to upload image!';
                    header('Location: edit_user_profile.php');
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid file type! Only JPG, JPEG, PNG, and GIF allowed.';
                header('Location: edit_user_profile.php');
                exit();
            }
        }
        
        // Update database
        $UPDATE = "UPDATE user_tbl SET u_name=?, u_gender=?, u_email=?, u_phone=?, u_address=?, u_about=?, u_img=? WHERE u_id=?";
        $stmt = $pdo->prepare($UPDATE);
        $stmt->execute([
            $u_name,
            $u_gender,
            $u_email,
            $u_phone,
            $u_address,
            $u_about,
            $imageName,
            $u_id
        ]);
        
        // Check if anything changed
        $dataChanged = (
            $u_name != $currentUser['u_name'] ||
            $u_gender != $currentUser['u_gender'] ||
            $u_email != $currentUser['u_email'] ||
            $u_phone != $currentUser['u_phone'] ||
            $u_address != $currentUser['u_address'] ||
            $u_about != $currentUser['u_about']
        );
        
        if($dataChanged || $imageChanged){
            $_SESSION['success'] = 'Profile updated successfully!';
        } else {
            $_SESSION['info'] = 'No changes made to the profile.';
        }
        header('Location: edit_user_profile.php');
        exit();
    }
    catch(PDOException $e){
        $_SESSION['error'] = 'Error updating User: ' . $e->getMessage();
        header('Location: edit_user_profile.php');
        exit();
    }
} else {
    header('Location: edit_user_profile.php');
    exit();
}
?>