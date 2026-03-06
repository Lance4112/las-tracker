<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'teacher') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $section_id = $_POST['section_id'];
    $teacher_id = $_SESSION['user_id'];

    // Collect all 20 values
    $vals = [];
    for($i=1; $i<=20; $i++) {
        $vals[] = $_POST["las_$i"];
    }

    // Check if record exists
    $check = $pdo->prepare("SELECT id FROM las_scores WHERE student_id = ? AND subject_id = ?");
    $check->execute([$student_id, $subject_id]);
    
    if ($check->fetch()) {
        // UPDATE existing row
        $setPart = "";
        for($i=1; $i<=20; $i++) { $setPart .= "las_$i = ?, "; }
        $setPart = rtrim($setPart, ", ");
        
        $sql = "UPDATE las_scores SET $setPart WHERE student_id = ? AND subject_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge($vals, [$student_id, $subject_id]));
    } else {
        // INSERT new row
        $cols = "";
        for($i=1; $i<=20; $i++) { $cols .= "las_$i, "; }
        $cols = rtrim($cols, ", ");
        $placeholders = str_repeat("?, ", 20);
        
        $sql = "INSERT INTO las_scores (student_id, subject_id, teacher_id, $cols) VALUES (?, ?, ?, $placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge([$student_id, $subject_id, $teacher_id], $vals));
    }

    // Redirect back with filters preserved
    header("Location: dashboard.php?subject=$subject_id&section=$section_id");
    exit();
}