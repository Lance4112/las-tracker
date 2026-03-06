<?php
session_start();
require 'db.php';

// 1. Initialize core variables immediately
$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'guest';
$user = null;
$records = [];
$selected_semester = $_GET['semester'] ?? '1';

// 2. Redirect teachers to their dashboard
if ($isLoggedIn && $role === 'teacher') {
    header("Location: dashboard.php");
    exit();
}

// 3. Fetch Student Data if logged in (The "Personal Portal" view)
if ($isLoggedIn && $role === 'user') {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("
        SELECT u.*, s.section_name, s.strand_id, s.grade_level 
        FROM users u 
        LEFT JOIN sections s ON u.section_id = s.id 
        WHERE u.id = ?
    ");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if ($user) {
        $profile_pic = !empty($user['profile_pix']) && file_exists('uploads/'.$user['profile_pix']) 
                        ? 'uploads/'.$user['profile_pix'] 
                        : 'https://ui-avatars.com/api/?background=random&name='.urlencode($user['username']);

        $user_grade = $user['grade_level'] ?? 12;
        $stmt = $pdo->prepare("
            SELECT s.subject_name, s.type, l.* FROM subjects s 
            LEFT JOIN las_scores l ON s.id = l.subject_id AND l.student_id = ? 
            WHERE s.semester = ? 
            AND s.grade_level = ? 
            AND (s.type = 'minor' OR s.strand_id = ?)
        ");
        $stmt->execute([$user_id, $selected_semester, $user_grade, $user['strand_id'] ?? 0]);
        $records = $stmt->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LAS Portal | Student Progress</title>
    <link rel="stylesheet" href="theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --table-sticky-bg: #ffffff; }
        [data-theme="dark"] { --table-sticky-bg: #1e1e1e; }

        /* Theme & Header */
        .theme-toggle-btn { background: var(--container-bg); border: 1px solid var(--border-color); color: var(--text-color); padding: 10px 18px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: 0.3s; }
        .student-card { display: flex; align-items: center; gap: 25px; margin: 20px 0; padding: 25px; }
        .profile-img { width: 120px; height: 120px; object-fit: cover; border-radius: 15px; border: 3px solid #3498db; }
        
        /* Info Badge Styling */
        .info-badge { padding: 4px 12px; border-radius: 6px; font-weight: bold; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; }
        .badge-blue { color: #3498db; background: rgba(52, 152, 219, 0.1); border: 1px solid rgba(52, 152, 219, 0.2); }
        .badge-gray { color: var(--text-color); background: var(--border-color); opacity: 0.9; }

        /* Search Bar & Dynamic Results */
        .home-search-container { max-width: 500px; margin: 0 auto 10px auto; position: relative; }
        .home-search-input { width: 100%; padding: 15px 20px 15px 45px; border-radius: 30px; border: 1px solid var(--border-color); background: var(--container-bg); color: var(--text-color); font-size: 1rem; outline: none; }
        .search-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); opacity: 0.5; color: var(--text-color); }
        
        #searchSuggestions { max-width: 800px; margin: 0 auto; background: var(--container-bg); border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); }
        .result-item { padding: 12px 15px; border-bottom: 1px solid var(--border-color); cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 15px; }
        .result-item img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #3498db; }
        .result-item:hover { background: rgba(52, 152, 219, 0.1); }

        /* Table & Sticky Columns */
        .table-container { margin-top: 20px; overflow-x: auto; border-radius: 12px; border: 1px solid var(--border-color); background: var(--table-sticky-bg); }
        table { border-collapse: separate; border-spacing: 0; width: 100%; min-width: 1200px; }
        th, td { border: 1px solid var(--border-color); padding: 12px; text-align: center; color: var(--text-color); }
        .sticky-col { position: sticky; left: 0; background-color: var(--table-sticky-bg) !important; z-index: 10; font-weight: bold; border-right: 2px solid var(--border-color); }
        
        .status-pass { color: #27ae60 !important; font-weight: bold; font-size: 1.2rem; }
        .btn-sm { padding: 5px 12px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.85rem; color: white; transition: 0.2s; }
        .logout-link { color: #e74c3c; text-decoration: none; font-weight: bold; margin-left: 15px; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.3s ease forwards; }
    </style>
</head>
<body class="gradient-active">

    <header style="display:flex; justify-content: space-between; align-items:center; padding: 20px 5%;">
        <h2>LAS Tracking Portal</h2>
        <div style="display: flex; align-items: center;">
            <button id="themeSwitcher" class="theme-toggle-btn">
                <i class="fas fa-moon"></i>
                <span id="themeLabel">Dark Mode</span>
            </button>
            <?php if ($isLoggedIn): ?>
                <a href="logout.php" class="logout-link">Logout</a>
            <?php endif; ?>
        </div>
    </header>

    <main style="padding: 0 5%;">
    <?php if ($isLoggedIn && $user): ?>
        <div class="student-card section">
            <img src="<?= $profile_pic ?>" alt="Profile" class="profile-img">
            <div style="flex-grow: 1;">
                <h1 style="margin:0; color: var(--text-color);"><?= htmlspecialchars($user['username']) ?></h1>
                
                <div style="margin: 12px 0; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                    <span class="info-badge badge-blue">
                        <i class="fas fa-id-card"></i> Student ID: <?= htmlspecialchars($user['student_id_number'] ?? 'N/A') ?>
                    </span>
                    <span class="info-badge badge-gray">
                        <i class="fas fa-graduation-cap"></i> Grade Level: <?= htmlspecialchars($user['grade_level'] ?? '12') ?>
                    </span>
                </div>

                <p style="margin: 5px 0; opacity: 0.8; color: var(--text-color);">
                    <i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email']) ?> | 
                    <i class="fas fa-users"></i> Section: <strong><?= htmlspecialchars($user['section_name'] ?? 'N/A') ?></strong>
                </p>
                
                <form method="GET" style="margin-top: 15px;">
                    <label style="font-size: 0.85rem; opacity: 0.7; display: block; margin-bottom: 5px;">Viewing Records for:</label>
                    <select name="semester" onchange="this.form.submit()" style="width:auto; padding: 10px 15px; border-radius: 8px; background: var(--container-bg); color: var(--text-color); border: 1px solid var(--border-color); cursor: pointer; font-weight: bold;">
                        <option value="1" <?= $selected_semester == '1' ? 'selected' : '' ?>>1st Semester</option>
                        <option value="2" <?= $selected_semester == '2' ? 'selected' : '' ?>>2nd Semester</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="table-container fade-in">
            <table>
                <thead>
                    <tr>
                        <th class="sticky-col">Subject Name</th>
                        <?php for($i=1; $i<=20; $i++) echo "<th>L$i</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr><td colspan="21" style="padding: 50px; opacity: 0.5; text-align: center;">No subjects found for Semester <?= htmlspecialchars($selected_semester) ?>.</td></tr>
                    <?php else: ?>
                        <?php foreach ($records as $row): ?>
                            <tr>
                                <td class="sticky-col" style="text-align: left; background: var(--table-sticky-bg);">
                                    <?= htmlspecialchars($row['subject_name']) ?>
                                    <small style="display:block; opacity: 0.5; font-weight:normal;"><?= ucfirst($row['type']) ?></small>
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

    <?php else: ?>
        <section class="card" style="text-align:center; padding: 60px 20px; margin-top: 50px;">
            <h2 style="margin-bottom: 30px;">Welcome to LAS Tracker</h2>
            
            <div class="home-search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="liveSearch" class="home-search-input" placeholder="Search Name, ID, Email, or Section...">
            </div>

            <div id="searchSuggestions"></div>

            <div id="studentDetailArea" style="margin-top: 30px;"></div>

            <p style="opacity: 0.6; margin-top: 30px;">Authorized personnel? <a href="login.php">Login here</a></p>
        </section>
    <?php endif; ?>
    </main>

    <script>
        // Theme Toggle Logic
        const themeBtn = document.getElementById('themeSwitcher');
        const themeLabel = document.getElementById('themeLabel');
        const applyTheme = (theme) => {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            themeLabel.innerText = theme === 'dark' ? 'Light Mode' : 'Dark Mode';
        };

        const currentTheme = localStorage.getItem('theme') || 'light';
        applyTheme(currentTheme);

        themeBtn.addEventListener('click', () => {
            const newTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            applyTheme(newTheme);
        });

        // AJAX Search & Semester Logic
        const liveSearch = document.getElementById('liveSearch');
        const suggestions = document.getElementById('searchSuggestions');
        const detailArea = document.getElementById('studentDetailArea');

        function searchWithPage(pageNumber = 1) {
            let query = liveSearch.value;
            if(query.length > 1) {
                fetch(`search_handler.php?q=${encodeURIComponent(query)}&page=${pageNumber}`)
                    .then(res => res.text())
                    .then(data => {
                        suggestions.innerHTML = data;
                    });
            } else {
                suggestions.innerHTML = "";
                detailArea.innerHTML = "";
            }
        }

        if(liveSearch) {
            liveSearch.addEventListener('input', () => searchWithPage(1));
        }

        function viewStudent(id, semester = 1) {
            suggestions.innerHTML = ""; 
            detailArea.innerHTML = `
                <div style="padding: 40px; color: var(--text-color);">
                    <i class="fas fa-circle-notch fa-spin"></i> Loading Semester ${semester} records...
                </div>`;
            
            fetch(`detail_handler.php?id=${id}&semester=${semester}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.text();
                })
                .then(data => {
                    detailArea.innerHTML = data;
                    detailArea.classList.add('fade-in');
                    detailArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    detailArea.innerHTML = "<p style='color:#e74c3c; padding: 20px;'>Error: Could not retrieve records.</p>";
                });
        }
    </script>
</body>
</html>