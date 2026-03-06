<?php
// fetch_student_info.php
session_start();
require 'db.php';

// Security: Only allow logged-in teachers to fetch data
if (isset($_SESSION['role']) && $_SESSION['role'] == 'teacher' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // UPDATED: Added student_id_number to the SELECT statement
    $stmt = $pdo->prepare("
        SELECT u.username, u.email, u.profile_pix, u.student_id_number, s.section_name 
        FROM users u 
        LEFT JOIN sections s ON u.section_id = s.id 
        WHERE u.id = ?
    ");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user) {
        $img = !empty($user['profile_pix']) ? 'uploads/'.$user['profile_pix'] : 'https://via.placeholder.com/150';
        
        echo "<div style='display:flex; gap:15px; align-items:center;'>";
        
        // Profile Image with high-contrast border
        echo "<img src='$img' style='width:65px; height:65px; object-fit:cover; border:2px solid #3498db; border-radius:8px;'>";
        
        echo "<div style='flex-grow: 1;'>";
        
        // NEW: Student ID Number highlighted in blue
        echo "<p style='margin:0 0 2px 0; font-size:0.75rem; color: #3498db; font-weight:bold; letter-spacing: 0.5px;'>" . 
             htmlspecialchars($user['student_id_number'] ?? 'NO ID') . "</p>";

        // Username in bright white
        echo "<h4 style='margin:0; color: #ffffff; font-size: 1.1rem; line-height:1.2;'>" . htmlspecialchars($user['username']) . "</h4>";
        
        // Email in a softer light gray
        echo "<p style='margin:4px 0; font-size:0.85rem; color: #bdc3c7;'>" . htmlspecialchars($user['email'] ?? 'No Email') . "</p>";
        
        // Section badge 
        if (!empty($user['section_name'])) {
            echo "<span style='display:inline-block; margin-top:5px; padding:2px 8px; background:#3498db; color:white; border-radius:4px; font-size:0.75rem; font-weight: 500;'>Section: " . htmlspecialchars($user['section_name']) . "</span>";
        }
        
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div style='padding: 10px; color: #e74c3c; font-weight: bold;'>Student data not found.</div>";
    }
} else {
    echo "<div style='padding: 10px; color: #e74c3c; font-weight: bold;'>Unauthorized request.</div>";
}
?>