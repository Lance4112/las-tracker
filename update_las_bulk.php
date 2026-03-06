<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'teacher') {
    $subject_id = (int)$_POST['subject_id'];
    $section_id = (int)$_POST['section_id'];
    $semester   = (int)$_POST['semester']; // Capture the semester from the form
    $teacher_id = (int)$_SESSION['user_id'];
    
    $active_students = $_POST['active_students'] ?? [];
    $submitted_scores = $_POST['scores'] ?? []; 

    if (empty($active_students)) {
        header("Location: dashboard.php?semester=$semester&subject=$subject_id&section=$section_id&status=no_students");
        exit();
    }

    foreach ($active_students as $student_id) {
        $student_id = (int)$student_id;
        $las_data = [];

        for ($i = 1; $i <= 20; $i++) {
            $las_data["las_$i"] = isset($submitted_scores[$student_id]["las_$i"]) ? '/' : '';
        }

        $columns = array_keys($las_data);
        $cols_sql = "student_id, subject_id, teacher_id, " . implode(', ', $columns);
        $placeholders = "?, ?, ?, " . implode(', ', array_fill(0, count($columns), '?'));
        
        $updateParts = [];
        foreach ($columns as $col) {
            $updateParts[] = "$col = VALUES($col)";
        }
        $updateParts[] = "teacher_id = VALUES(teacher_id)";

        $sql = "INSERT INTO las_scores ($cols_sql) VALUES ($placeholders) 
                ON DUPLICATE KEY UPDATE " . implode(', ', $updateParts);
        
        $params = array_merge([$student_id, $subject_id, $teacher_id], array_values($las_data));
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    // Crucial: Redirect including the semester to keep the teacher on the current view
    header("Location: dashboard.php?semester=$semester&subject=$subject_id&section=$section_id&status=success");
    exit();
} else {
    header("Location: login.php");
    exit();
}