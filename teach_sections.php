<?php
require 'db.php';

$strand_id = $_GET['strand_id'] ?? 0;

$stmt = $pdo->prepare("SELECT id, section_name, grade_level FROM sections WHERE strand_id = ? ORDER BY grade_level ASC, section_name ASC");
$stmt->execute([$strand_id]);
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Send the data back to the dashboard as JSON
header('Content-Type: application/json');
echo json_encode($sections);