<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Capture inputs safely
    $user_input_name = $_POST['username'] ?? '';
    $user_input_pass = $_POST['password'] ?? ''; 

    // 2. Basic validation
    if (empty($user_input_name) || empty($user_input_pass)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }

    // 3. Fetch user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user_input_name]);
    $user = $stmt->fetch();

    /**
     * 4. PASSWORD VERIFICATION
     * IMPORTANT: We use $user['password_hash'] because that is the 
     * actual name of the column in your database.
     */
    if ($user && password_verify($user_input_pass, $user['password_hash'])) {
        
        // Regenerate session ID for security
        session_regenerate_id();

        // Store session data
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // 5. Role-Based Redirection
        if ($user['role'] === 'teacher') {
            header("Location: dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();

    } else {
        // 6. Failed Login
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
} else {
    // Redirect if they try to access this file directly via URL
    header("Location: login.php");
    exit();
}