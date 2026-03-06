<?php
require 'db.php';

// Ensure ID and Semester are treated as integers to match DB column types
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sem = isset($_GET['semester']) ? (int)$_GET['semester'] : 1; 

// Fetch student info
$stmt = $pdo->prepare("
    SELECT u.*, s.section_name, s.strand_id, s.grade_level 
    FROM users u 
    LEFT JOIN sections s ON u.section_id = s.id 
    WHERE u.id = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<div style='color:red; padding:20px;'>Student not found.</div>";
    exit();
}

$photoPath = 'uploads/' . $user['profile_pix'];
$displayPhoto = (!empty($user['profile_pix']) && file_exists($photoPath)) 
                ? $photoPath 
                : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($user['username']);

// FETCH LOGIC
$stmt = $pdo->prepare("
    SELECT s.subject_name, s.type, l.* FROM subjects s 
    LEFT JOIN las_scores l ON s.id = l.subject_id AND l.student_id = :sid
    WHERE s.grade_level = :grade 
    AND s.semester = :sem
    AND (s.type = 'minor' OR s.strand_id = :strand)
");

$stmt->execute([
    'sid'    => $id,
    'grade'  => $user['grade_level'] ?? 12,
    'sem'    => $sem,
    'strand' => $user['strand_id'] ?? 0
]);
$records = $stmt->fetchAll();
?>

<div class="section" style="padding: 25px; margin-top: 20px; border: 1px solid var(--border-color); border-radius: 12px; background: var(--container-bg); animation: fadeIn 0.3s ease;">
    <div style="display: flex; align-items: flex-start; gap: 20px; margin-bottom: 25px;">
        <img src="<?= $displayPhoto ?>" style="width: 90px; height: 90px; border-radius: 12px; object-fit: cover; border: 3px solid #3498db; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        
        <div style="flex-grow: 1; text-align: left;">
            <h2 style="margin: 0; color: var(--text-color); font-size: 1.6rem;"><?= htmlspecialchars($user['username']) ?></h2>
            
            <div style="margin: 8px 0; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                <span style="color: #3498db; font-weight: bold; background: rgba(52, 152, 219, 0.1); padding: 4px 10px; border-radius: 6px; font-size: 0.9rem;">
                    <i class="fas fa-id-card"></i> Student ID: <?= htmlspecialchars($user['student_id_number'] ?? 'N/A') ?>
                </span>
                <span style="color: var(--text-color); font-weight: 600; background: var(--border-color); padding: 4px 10px; border-radius: 6px; font-size: 0.9rem; opacity: 0.9;">
                    <i class="fas fa-graduation-cap"></i> Grade Level: <?= htmlspecialchars($user['grade_level'] ?? '12') ?>
                </span>
            </div>

            <p style="margin: 5px 0; opacity: 0.7; color: var(--text-color); font-size: 0.95rem;">
                <i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email']) ?> | 
                <i class="fas fa-users"></i> Section: <strong><?= htmlspecialchars($user['section_name'] ?? 'N/A') ?></strong>
            </p>
            
            <div style="margin-top: 15px; display: flex; gap: 8px;">
                <button class="btn-sm" 
                        style="background: <?= (int)$sem === 1 ? '#3498db' : '#95a5a6' ?>; padding: 8px 16px; font-weight: bold;" 
                        onclick="viewStudent(<?= $id ?>, 1)">
                    1st Semester
                </button>
                <button class="btn-sm" 
                        style="background: <?= (int)$sem === 2 ? '#3498db' : '#95a5a6' ?>; padding: 8px 16px; font-weight: bold;" 
                        onclick="viewStudent(<?= $id ?>, 2)">
                    2nd Semester
                </button>
            </div>
        </div>

        <button onclick="document.getElementById('studentDetailArea').innerHTML=''" style="background:none; border:none; color:#e74c3c; cursor:pointer; font-size: 1.8rem; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            <i class="fas fa-times-circle"></i>
        </button>
    </div>

    <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 20px;">

    <h4 style="color: var(--text-color); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
        <span style="width: 4px; height: 20px; background: #3498db; display: inline-block; border-radius: 2px;"></span>
        Showing: <?= $sem == 1 ? 'First' : 'Second' ?> Semester Records
    </h4>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="sticky-col">Subject</th>
                    <?php for($i=1; $i<=20; $i++) echo "<th>L$i</th>"; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($records)): ?>
                    <tr>
                        <td colspan="21" style="padding: 50px; opacity: 0.5; text-align: center;">
                            <i class="fas fa-folder-open" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                            No subjects found for Semester <?= $sem ?> in Grade <?= htmlspecialchars($user['grade_level']) ?>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($records as $row): ?>
                    <tr>
                        <td class="sticky-col" style="text-align: left; background: var(--table-sticky-bg);">
                            <?= htmlspecialchars($row['subject_name']) ?>
                            <small style="display:block; opacity: 0.5; font-weight: normal;"><?= ucfirst($row['type']) ?></small>
                        </td>
                        <?php for($i=1; $i<=20; $i++): 
                            $val = $row['las_'.$i] ?? ''; 
                            echo "<td class='status-pass' style='font-size: 1.1rem;'>".($val === '/' ? '✓' : '')."</td>"; 
                        endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>