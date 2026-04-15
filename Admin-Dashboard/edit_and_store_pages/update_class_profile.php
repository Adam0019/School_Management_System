<?php
session_start();
include('../../Config/dbcon.php'); // Adjust path as needed

// Check if user is authenticated
if(!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL){
    header('Location: ../../Login/logout.php');
    exit();
}

if(isset($_POST['submit'])){
    try{
        $c_id = $_POST['c_id'];
        $c_name = $_POST['c_name'];
        $sub_id = $_POST['sub_id'];
        $sub_one = $_POST['sub_one'];
        $sub_two = $_POST['sub_two'];
        $sub_three = $_POST['sub_three'];
        $sub_four = $_POST['sub_four'];
        $sub_five = $_POST['sub_five'];
        $sub_six = $_POST['sub_six'];
        $sub_seven = $_POST['sub_seven'];
        $sub_eight = $_POST['sub_eight'];
        $sub_nine = $_POST['sub_nine'];

        // Get the current data from database
        $SELECT = "SELECT * FROM class_tbl WHERE c_id=?";
        $stmt_fetch = $pdo->prepare($SELECT);
        $stmt_fetch->execute([$c_id]);
        $currentData = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        // Handle image upload if needed (assuming class profile has an image, adjust as necessary)
        // For example, if class profile has an image field 'c_img':
        $current_image = $currentData['c_img'] ?? null;
        $image_name = $current_image; // Keep current image by default
        $imageChanged = false;
        if(isset($_FILES['c_img']) && $_FILES['c_img']['error'] == 0){
            $file = $_FILES['c_img'];
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            
            // Get file extension
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Allowed extensions
            $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
            
            if(in_array($file_ext, $allowed)){
                // Check file size (max 5MB)
                if($file_size <= 5242880){
                    // Generate unique filename
                    $new_file_name = 'class_' . $c_id . '_' . time() . '.' . $file_ext;
                    
                    // Set upload directory
                    $upload_dir = '../assets/image/';
                    $upload_path = $upload_dir . $new_file_name;
                    
                    // Upload file
                    if(move_uploaded_file($file_tmp, $upload_path)){
                        // Update image name to be stored in database
                        $image_name = $new_file_name;
                        $imageChanged = true;
                    } else {
                        throw new Exception('Failed to upload image.');
                    }
                } else {
                    throw new Exception('File size exceeds the maximum limit of 5MB.');
                }
            } else {
                throw new Exception('Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.');
            }
        }

        // Update class details in database
        $UPDATE = "UPDATE class_tbl SET c_name=?, sub_id=?, sub_one=?, sub_two=?, sub_three=?, sub_four=?, sub_five=?, sub_six=?, sub_seven=?, sub_eight=?, sub_nine=?, c_img=? WHERE c_id=?";
        $stmt_update = $pdo->prepare($UPDATE);
        $stmt_update->execute([$c_name, $sub_id, $sub_one, $sub_two, $sub_three, $sub_four, $sub_five, $sub_six, $sub_seven, $sub_eight, $sub_nine, $image_name, $c_id]);

        // Delete old image if a new one was uploaded and the old one is not a default image
        if($imageChanged && $current_image != "" && $current_image != "class_default.jpg"){
            $old_image_path = $upload_dir . $current_image;
            if(file_exists($old_image_path)){
                unlink($old_image_path);
            }
        }
        $dataChanged = (
            $currentData['c_name'] != $c_name ||
            $currentData['sub_id'] != $sub_id ||
            $currentData['sub_one'] != $sub_one ||
            $currentData['sub_two'] != $sub_two ||
            $currentData['sub_three'] != $sub_three ||
            $currentData['sub_four'] != $sub_four ||
            $currentData['sub_five'] != $sub_five ||
            $currentData['sub_six'] != $sub_six ||
            $currentData['sub_seven'] != $sub_seven ||
            $currentData['sub_eight'] != $sub_eight ||
            $currentData['sub_nine'] != $sub_nine ||
            ($imageChanged && $current_image != $image_name)
        );
        if($dataChanged){
            $_SESSION['success'] = "Class profile updated successfully.";
        } else {
            $_SESSION['info'] = "No changes were made to the class profile.";
        }
        header("Location: edit_class_profile.php?c_id=$c_id");
        exit();
    } catch(Exception $e){
        $_SESSION['error'] = $e->getMessage();
        header("Location: edit_class_profile.php?c_id=$c_id");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../pages/classes_page.php");
    exit();
}
?>