<?php
session_start();
require 'db.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// 2. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['email'];
    $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->execute([$new_email, $user_id]);

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

        $file_ext = pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);
        $new_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_name;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $old_photo_query = $pdo->prepare("SELECT profile_pix FROM users WHERE id = ?");
            $old_photo_query->execute([$user_id]);
            $old_photo = $old_photo_query->fetchColumn();
            
            if ($old_photo && file_exists($target_dir . $old_photo)) {
                unlink($target_dir . $old_photo);
            }

            $stmt = $pdo->prepare("UPDATE users SET profile_pix = ? WHERE id = ?");
            $stmt->execute([$new_name, $user_id]);
        }
    }
    
    $redirect = ($role === 'teacher') ? 'dashboard.php' : 'index.php';
    header("Location: $redirect?msg=updated");
    exit();
}

// 3. Fetch Current Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | LAS Tracker</title>
    <link rel="stylesheet" href="theme.css">
    <style>
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }
        /* Inheriting Glassmorphism from theme.css */
        .profile-card {
            padding: 30px;
            width: 100%;
            max-width: 500px;
        }
        .current-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 20px auto;
            display: block;
            border: 4px solid var(--accent-color, #3498db);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold;
            color: var(--text-color); 
        }
        .back-link { 
            text-decoration: none; 
            font-weight: bold; 
            color: var(--text-color);
            transition: opacity 0.2s;
        }
        .back-link:hover { opacity: 0.7; }
        
        input[type="email"], input[type="file"], input[type="text"] {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="gradient-active">

    <header style="display: flex; justify-content: space-between; align-items: center; padding: 15px 5%;">
        <?php $back = ($role === 'teacher') ? 'dashboard.php' : 'index.php'; ?>
        <a href="<?= $back ?>" class="back-link">← Back to Home</a>
        
        <?php include 'settings.php'; ?>
    </header>

    <div class="profile-container">
        <section class="profile-card">
            <h2 style="text-align: center; color: var(--text-color); margin-top: 0;">My Profile</h2>
            
            <form method="POST" enctype="multipart/form-data">
                <?php 
                    $pic = !empty($user['profile_pix']) && file_exists("uploads/".$user['profile_pix']) 
                           ? "uploads/".$user['profile_pix'] 
                           : "https://ui-avatars.com/api/?background=random&name=".urlencode($user['username']);
                ?>
                <img src="<?= $pic ?>" class="current-pic" alt="Profile preview">

                <div class="form-group">
                    <label>Username (Fixed):</label>
                    <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled style="opacity: 0.5; cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Change Profile Photo:</label>
                    <input type="file" name="profile_pic" accept="image/*">
                </div>

                <button type="submit" style="width: 100%;">
                    UPDATE PROFILE
                </button>
            </form>
        </section>
    </div>

</body>
</html>