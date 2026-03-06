<?php
require 'db.php';

// Fetch the student's primary ID from the URL
$id = $_GET['id'] ?? 0;

// 1. Fetch Student/Section info first to determine which subjects they should see
$stmt = $pdo->prepare("
    SELECT u.username, u.id, s.section_name, s.strand_id, s.grade_level 
    FROM users u 
    LEFT JOIN sections s ON u.section_id = s.id 
    WHERE u.id = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<p style='color:red; padding:20px;'>Record not found.</p>";
    exit();
}

// 2. Fetch Subjects and the LAS scores linked to this student's ID
$stmt = $pdo->prepare("
    SELECT s.subject_name, s.type, l.* FROM subjects s 
    LEFT JOIN las_scores l ON s.id = l.subject_id AND l.student_id = ? 
    WHERE s.grade_level = ? 
    AND (s.type = 'minor' OR s.strand_id = ?)
");
$stmt->execute([$id, $user['grade_level'] ?? 12, $user['strand_id'] ?? 0]);
$records = $stmt->fetchAll();

?>
<div class="student-info-header" style="text-align: left; margin-bottom: 20px; padding: 15px; background: rgba(52, 152, 219, 0.1); border-radius: 8px;">
    <h3 style="margin: 0;">LAS Progress for: <?= htmlspecialchars($user['username']) ?></h3>
    <p style="margin: 5px 0 0 0; opacity: 0.8;">
        <strong>System ID:</strong> <?= htmlspecialchars($user['id']) ?> | 
        <strong>Section:</strong> <?= htmlspecialchars($user['section_name'] ?? 'Unassigned') ?>
    </p>
</div>

<div class='table-container'>
    <table>
        <thead>
            <tr>
                <th class="sticky-col">Subject</th>
                <?php for($i=1; $i<=20; $i++) echo "<th>L$i</th>"; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($records)): ?>
                <tr><td colspan="21">No academic records found for this student.</td></tr>
            <?php else: ?>
                <?php foreach ($records as $row): ?>
                <tr>
                    <td class="sticky-col" style="text-align: left;">
                        <?= htmlspecialchars($row['subject_name']) ?>
                    </td>
                    <?php for($i=1; $i<=20; $i++): 
                        $val = $row['las_'.$i] ?? ''; ?>
                        <td class="status-pass"><?= ($val === '/') ? '✓' : '' ?></td>
                    <?php endfor; ?>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<button onclick="document.getElementById('studentDetailView').innerHTML=''" class="btn" style="margin-top: 20px; background: #e74c3c; cursor: pointer;">
    Close Progress View
</button>