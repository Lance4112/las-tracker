<?php
session_start();
require 'db.php';

if (isset($_POST['submit']) && isset($_FILES['profile_pix'])) {
    $user_id = $_SESSION['user_id'];
    $img_name = $_FILES['profile_pix']['name'];
    $tmp_name = $_FILES['profile_pix']['tmp_name'];
    
    // Create a unique name to avoid overwriting
    $new_img_name = time() . '_' . $img_name;
    $upload_path = 'uploads/' . $new_img_name;

    // 1. Move file to the folder
    if (move_uploaded_file($tmp_name, $upload_path)) {
        
        // 2. Update the Database
        $stmt = $pdo->prepare("UPDATE users SET profile_pix = ? WHERE id = ?");
        if ($stmt->execute([$new_img_name, $user_id])) {
            header("Location: index.php?success=uploaded");
        }
    } else {
        echo "Failed to upload file to the folder.";
    }
}
?>