<?php
require 'db.php';

$query = $_GET['q'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Reduced to 5 for better UI on search results
$offset = ($page - 1) * $limit;

if (strlen($query) > 1) {
    $searchTerm = "%$query%";

    // 1. Get total count for pagination
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) FROM users u 
        LEFT JOIN sections s ON u.section_id = s.id 
        WHERE (u.username LIKE :q1 OR u.student_id_number LIKE :q2 OR s.section_name LIKE :q3)
        AND u.role = 'user'
    ");
    $countStmt->execute([':q1' => $searchTerm, ':q2' => $searchTerm, ':q3' => $searchTerm]);
    $totalResults = $countStmt->fetchColumn();
    $totalPages = ceil($totalResults / $limit);

    // 2. Fetch results with explicit Integer binding for LIMIT/OFFSET
    $stmt = $pdo->prepare("
        SELECT u.*, s.section_name 
        FROM users u 
        LEFT JOIN sections s ON u.section_id = s.id 
        WHERE (u.username LIKE :q1 OR u.student_id_number LIKE :q2 OR s.section_name LIKE :q3)
        AND u.role = 'user'
        LIMIT :limit OFFSET :offset
    ");
    
    $stmt->bindValue(':q1', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':q2', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':q3', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll();

    if ($results) {
        foreach ($results as $row) {
            $photo = !empty($row['profile_pix']) && file_exists('uploads/'.$row['profile_pix']) 
                     ? 'uploads/'.$row['profile_pix'] 
                     : 'https://ui-avatars.com/api/?background=random&name='.urlencode($row['username']);
            
            // Crucial: Use onclick for the result item
            echo "
            <div class='result-item' onclick='viewStudent({$row['id']}, 1)'>
                <img src='$photo' alt='Profile'>
                <div style='text-align: left;'>
                    <div style='font-weight: bold; color: var(--text-color);'>".htmlspecialchars($row['username'])."</div>
                    <div style='font-size: 0.8rem; color: #3498db;'>ID: ".htmlspecialchars($row['student_id_number'] ?? 'N/A')."</div>
                </div>
            </div>";
        }

        // 3. Pagination Controls
        if ($totalPages > 1) {
            echo "<div style='padding: 12px; display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.03); border-top: 1px solid var(--border-color);'>";
            
            // PREVIOUS BUTTON
            if ($page > 1) {
                echo "<button type='button' onclick='event.stopPropagation(); window.searchWithPage(".($page - 1).")' style='cursor:pointer; border:none; background:none; color:#3498db; font-weight:bold;'>← Prev</button>";
            } else {
                echo "<span></span>"; 
            }

            echo "<span style='font-size: 0.8rem; opacity: 0.6; color: var(--text-color);'>Page $page of $totalPages</span>";

            // NEXT BUTTON
            if ($page < $totalPages) {
                echo "<button type='button' onclick='event.stopPropagation(); window.searchWithPage(".($page + 1).")' style='cursor:pointer; border:none; background:none; color:#3498db; font-weight:bold;'>Next →</button>";
            } else {
                echo "<span></span>";
            }
            echo "</div>";
        }
    } else {
        echo "<div style='padding: 20px; text-align: center; opacity: 0.6; color: var(--text-color);'>No students found.</div>";
    }
}
?>
