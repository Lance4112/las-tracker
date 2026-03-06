<?php
require 'db.php';

$query = $_GET['q'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

if (strlen($query) > 1) {
    // We use unique placeholders (:q1, :q2, etc.) to avoid the "Invalid parameter number" error
    $stmt = $pdo->prepare("
        SELECT u.*, s.section_name 
        FROM users u 
        LEFT JOIN sections s ON u.section_id = s.id 
        WHERE (u.username LIKE :q1 
           OR u.email LIKE :q2 
           OR u.student_id_number LIKE :q3 
           OR s.section_name LIKE :q4)
        AND u.role = 'user'
        LIMIT :limit OFFSET :offset
    ");

    $searchTerm = "%$query%";
    
    // Bind the search term to each unique placeholder
    $stmt->bindValue(':q1', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':q2', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':q3', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':q4', $searchTerm, PDO::PARAM_STR);
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $results = $stmt->fetchAll();

    if ($results) {
        foreach ($results as $row) {
            $photo = !empty($row['profile_pix']) && file_exists('uploads/'.$row['profile_pix']) 
                     ? 'uploads/'.$row['profile_pix'] 
                     : 'https://ui-avatars.com/api/?background=random&name='.urlencode($row['username']);
            
            echo "<div class='result-item' onclick='viewStudent({$row['id']}, 1)'>
                    <img src='$photo' alt='Profile'>
                    <div style='text-align: left;'>
                        <div style='font-weight: bold; color: var(--text-color);'>".htmlspecialchars($row['username'])."</div>
                        <div style='font-size: 0.8rem; color: #3498db; font-weight: bold;'>
                            <i class='fas fa-id-card'></i> ".htmlspecialchars($row['student_id_number'] ?? 'No ID Set')."
                        </div>
                        <div style='font-size: 0.75rem; opacity: 0.7; color: var(--text-color);'>
                            Section: ".htmlspecialchars($row['section_name'] ?? 'N/A')."
                        </div>
                    </div>
                  </div>";
        }
    } else {
        echo "<div style='padding: 15px; opacity: 0.6; color: var(--text-color);'>No students found matching '$query'.</div>";
    }
}
?>