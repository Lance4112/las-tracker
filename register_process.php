<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the new student_id_number field
    $student_id_num = $_POST['student_id_number']; 
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $section_id = $_POST['section_id'];

    // Role is automatically set to 'user' for students
    $role = 'user'; 
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {
        // Updated SQL to include the student_id_number column
        $sql = "INSERT INTO users (student_id_number, username, email, password_hash, role, section_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Execute with the new variable included in the array
        if ($stmt->execute([$student_id_num, $user, $email, $hashed_password, $role, $section_id])) {
            header("Location: login.php?success=registered");
            exit();
        }
    } catch (PDOException $e) {
        // Check if the error is due to a duplicate Student ID or Email
        if ($e->getCode() == 23000) {
            die("Error: Student ID or Email already exists.");
        } else {
            die("Error: " . $e->getMessage());
        }
    }
}