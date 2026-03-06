<?php
require 'db.php';

$search = $_GET['query'] ?? '';

if (strlen($search) < 2) {
    exit(); // Avoid searching for very short strings
}

// Search by Name, Section Name, or Strand/Course
// We use 'u.id' as the primary identifier
$stmt = $pdo->prepare("
    SELECT u.id, u.username, s.section_name, st.strand_name 
    FROM users u
    LEFT JOIN sections s ON u.section_id = s.id
    LEFT JOIN strands st ON s.strand_id = st.id
    WHERE (u.username LIKE ? OR s.section_name LIKE ? OR st.strand_name LIKE ?)
    AND u.role = 'user'
    LIMIT 10
");

$term = "%$search%";
$stmt->execute([$term, $term, $term]);
$results = $stmt->fetchAll();

if ($results) {
    echo "<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Section</th>
                    <th>Course/Strand</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($results as $row) {
        // We pass the primary key 'id' to the JavaScript function
        echo "<tr class='search-result-row' onclick='viewStudentRecord(" . $row['id'] . ")'>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td><strong>" . htmlspecialchars($row['username']) . "</strong></td>
                <td>" . htmlspecialchars($row['section_name'] ?? 'N/A') . "</td>
                <td>" . htmlspecialchars($row['strand_name'] ?? 'N/A') . "</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p style='padding:20px;'>No students found matching your search.</p>";
}
?>