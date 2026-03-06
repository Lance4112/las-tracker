<?php
session_start();
require 'db.php';

// Access Control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') { 
    header("Location: index.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$selected_semester = $_GET['semester'] ?? '1'; 
$selected_strand = $_GET['strand'] ?? '';
$selected_subject = $_GET['subject'] ?? '';
$selected_section = $_GET['section'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard | LAS Tracker</title>
    <link rel="stylesheet" href="theme.css">
    <style>
    /* 1. MATCH THEME.CSS LOGIC */
    :root {
        --table-sticky-bg: #ffffff; /* Light Mode default */
    }

    /* This targets the data-theme attribute used in your theme.css */
    [data-theme="dark"] {
        --table-sticky-bg: #1e1e1e; /* Matches your --grad-1 / dark background */
    }

    .table-container { 
        margin-top: 20px; 
        position: relative;
        overflow: visible !important; 
    }
    
    .table-scroll {
        overflow-x: auto;
        max-height: 650px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        /* We use a solid background here so names don't overlap when scrolling */
        background: var(--table-sticky-bg); 
        position: relative;
        z-index: 1;
        overflow-y: visible !important; 
    }

    table { 
        border-collapse: separate; 
        border-spacing: 0; 
        min-width: 1500px; 
        width: 100%; 
        background: transparent; 
        color: var(--text-color); 
    }

    th, td { 
        border: 1px solid var(--border-color); 
        padding: 0; 
        text-align: center; 
    }
    
    /* ADAPTIVE HEADERS */
    th { 
        padding: 12px; 
        background-color: var(--table-sticky-bg) !important; 
        position: sticky; 
        top: 0; 
        z-index: 100; 
        color: var(--text-color);
    }
    
    /* ADAPTIVE STICKY COLUMN (Student Names) */
    .sticky-col { 
        position: sticky; 
        left: 0; 
        /* Essential: Use a solid color, not transparent/rgba, so text behind it is hidden */
        background-color: var(--table-sticky-bg) !important; 
        z-index: 90; 
        min-width: 200px; 
        font-weight: bold; 
        border-right: 2px solid var(--border-color);
        padding: 10px !important; 
        color: var(--text-color) !important; 
    }

    /* TOOLTIP */
    .student-hover-cell { 
        cursor: help; 
        color: #3498db; 
        position: relative; 
        text-decoration: underline dotted;
    }
    
    .profile-tooltip {
        display: none; 
        position: absolute; 
        left: 105%; 
        top: 0;
        width: 320px; 
        background: #000000 !important; 
        border: 2px solid #3498db;
        border-radius: 12px; 
        box-shadow: 10px 10px 30px rgba(0,0,0,0.8);
        z-index: 999999 !important; 
        padding: 15px; 
        pointer-events: none;
        text-align: left;
        color: #ffffff;
    }
    
    .student-hover-cell:hover .profile-tooltip { 
        display: block !important; 
    }

    /* CHECKBOXES */
    .las-check { display: none; } 
    .las-label {
        display: flex; align-items: center; justify-content: center;
        width: 100%; height: 45px; cursor: pointer; margin: 0; transition: 0.2s;
    }
    .las-label:hover { background: rgba(52, 152, 219, 0.1); }
    .las-check:checked + .las-label { background-color: #27ae60; color: white; }
    .las-check:checked + .las-label::after { content: "✓"; }

    /* ADAPTIVE SAVE BAR */
    .save-bar {
        position: sticky; bottom: 0; 
        background: var(--table-sticky-bg) !important;
        padding: 20px; text-align: right; 
        border-top: 2px solid var(--border-color);
        z-index: 110;
        margin-top: 0 !important; /* Prevent gaps */
    }
</style>
</head>
<body class="gradient-active">
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 20px 5%;">
        <h1>Teacher Dashboard</h1>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span>Teacher: <strong><?= htmlspecialchars($username) ?></strong></span>
            <?php include 'settings.php'; ?>
        </div>
    </header>

    <main style="padding: 0 5% 50px 5%;">
        <section class="section" style="margin-bottom: 30px;">
            <form method="GET">
                <div style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                    <div><label>Semester</label>
                        <select name="semester" onchange="this.form.submit()">
                            <option value="1" <?= $selected_semester == '1' ? 'selected' : '' ?>>1st</option>
                            <option value="2" <?= $selected_semester == '2' ? 'selected' : '' ?>>2nd</option>
                        </select>
                    </div>
                    <div><label>Strand</label>
                        <select name="strand" onchange="this.form.submit()">
                            <option value="">-- Select Strand --</option>
                            <?php
                            $strands = $pdo->query("SELECT * FROM strands")->fetchAll();
                            foreach($strands as $str) {
                                $sel = ($selected_strand == $str['id']) ? 'selected' : '';
                                echo "<option value='{$str['id']}' $sel>{$str['strand_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div><label>Subject</label>
                        <select name="subject">
                            <option value="">-- Select Subject --</option>
                            <?php
                            if ($selected_strand) {
                                $stmt = $pdo->prepare("SELECT id, subject_name FROM subjects WHERE semester = ? AND (type = 'minor' OR strand_id = ?)");
                                $stmt->execute([$selected_semester, $selected_strand]);
                                while($s = $stmt->fetch()) {
                                    $sel = ($selected_subject == $s['id']) ? 'selected' : '';
                                    echo "<option value='{$s['id']}' $sel>{$s['subject_name']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div><label>Section</label>
                        <select name="section">
                            <option value="">-- Select Section --</option>
                            <?php
                            if ($selected_strand) {
                                $stmt = $pdo->prepare("SELECT * FROM sections WHERE strand_id = ?");
                                $stmt->execute([$selected_strand]);
                                while($sec = $stmt->fetch()) {
                                    $sel = ($selected_section == $sec['id']) ? 'selected' : '';
                                    echo "<option value='{$sec['id']}' $sel>G{$sec['grade_level']} - {$sec['section_name']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit">Load Sheet</button>
                </div>
            </form>
        </section>

        <?php if ($selected_section && $selected_subject): ?>
            <form action="update_las_bulk.php" method="POST">
                <input type="hidden" name="subject_id" value="<?= $selected_subject ?>">
                <input type="hidden" name="section_id" value="<?= $selected_section ?>">
                
                <div class="table-container">
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th class="sticky-col">Student Name</th>
                                    <?php for($i=1; $i<=20; $i++) echo "<th>L$i</th>"; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->prepare("
                                    SELECT u.id as student_id, u.username, 
                                           l.las_1, l.las_2, l.las_3, l.las_4, l.las_5, 
                                           l.las_6, l.las_7, l.las_8, l.las_9, l.las_10,
                                           l.las_11, l.las_12, l.las_13, l.las_14, l.las_15,
                                           l.las_16, l.las_17, l.las_18, l.las_19, l.las_20
                                    FROM users u 
                                    LEFT JOIN las_scores l ON u.id = l.student_id AND l.subject_id = ?
                                    WHERE u.role = 'user' AND u.section_id = ?
                                    ORDER BY u.username ASC
                                ");
                                $stmt->execute([$selected_subject, $selected_section]);
                                $students = $stmt->fetchAll();

                                foreach($students as $row): 
                                    $sid = $row['student_id'];
                                ?>
                                    <tr>
                                        <td class="sticky-col student-hover-cell" data-student-id="<?= $sid ?>">
                                             <?= htmlspecialchars($row['username']) ?>
                                             <div class="profile-tooltip">
                                                 <div class="tooltip-content" style="color: white;">Loading details...</div>
                                             </div>
                                             <input type="hidden" name="active_students[]" value="<?= $sid ?>">
                                        </td>
                                        <?php for($i=1; $i<=20; $i++): 
                                            $val = $row['las_'.$i] ?? ''; 
                                            $isChecked = (trim($val) === '/') ? 'checked' : '';
                                        ?>
                                            <td>
                                                <input type="checkbox" name="scores[<?=$sid?>][las_<?=$i?>]" 
                                                       id="check_<?=$sid?>_<?=$i?>" class="las-check" 
                                                       value="/" <?=$isChecked?>>
                                                <label for="check_<?=$sid?>_<?=$i?>" class="las-label"></label>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="save-bar">
                    <button type="submit" style="background: #27ae60 !important; padding: 12px 40px;">SAVE ALL CHANGES</button>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <script>
    // Improved with Event Delegation and safer ID checking
    document.addEventListener('mouseover', function (e) {
        const cell = e.target.closest('.student-hover-cell');
        if (!cell) return;

        const studentId = cell.getAttribute('data-student-id');
        const tooltipContainer = cell.querySelector('.tooltip-content');

        // Only fetch if studentId is valid (>0) and content is still loading
        if (studentId && studentId !== "0" && tooltipContainer.innerText.includes('Loading')) {
            fetch(`fetch_student_info.php?id=${studentId}`)
                .then(response => response.text())
                .then(data => {
                    tooltipContainer.innerHTML = data;
                })
                .catch(() => {
                    tooltipContainer.innerHTML = 'Error loading profile.';
                });
        }
    });
    </script>
</body>
</html>