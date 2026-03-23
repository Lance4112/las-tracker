<?php
session_start();
require 'db.php';

// Check if a file was even uploaded and the user is logged in
if (!isset($_FILES['profile_pix']) || !isset($_SESSION['user_id'])) {
    header("Location: index.php?error=uploadfailed");
    exit();
}

$user_id = $_SESSION['user_id'];
$file = $_FILES['profile_pix'];

// Basic validation: Check for actual file data
if ($file['error'] !== UPLOAD_ERR_OK) {
    header("Location: index.php?error=invalidfile");
    exit();
}

// 1. Validate File Size (Optional but recommended, e.g., 5MB limit)
$max_size = 5 * 1024 * 1024; // 5 Megabytes
if ($file['size'] > $max_size) {
    header("Location: index.php?error=filetoolarge");
    exit();
}

// 2. Validate File Type (MIME type check)
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime_type = $finfo->file($file['tmp_name']);
$allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];

if (!in_array($mime_type, $allowed_mimes)) {
    header("Location: index.php?error=invalidfiletype");
    exit();
}

// 3. Define the upload path
$upload_dir = 'uploads/';
// Create the folder if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// 4. Generate a unique filename to prevent overwriting
$original_filename = pathinfo($file['name'], PATHINFO_FILENAME);
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
// Unique name based on user ID and a unique ID
$new_filename = 'user_' . $user_id . '_' . uniqid() . '.' . $extension;
$destination = $upload_dir . $new_filename;

// 5. Attempt to move the file to the destination
if (move_uploaded_file($file['tmp_name'], $destination)) {
    // 6. UPDATE the database profile_pix column
    $stmt = $pdo->prepare("UPDATE users SET profile_pix = ? WHERE id = ?");
    if ($stmt->execute([$new_filename, $user_id])) {
        // Success! Redirect back to index.php
        header("Location: index.php?success=profileupdated");
        exit();
    } else {
        // DB error (unlikely, but good to handle)
        unlink($destination); // Delete the uploaded file if DB fails
        header("Location: index.php?error=dberror");
        exit();
    }
} else {
    // File system error (check folder permissions)
    header("Location: index.php?error=movesystemerror");
    exit();
}
