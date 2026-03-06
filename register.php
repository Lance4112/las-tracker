<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | LAS Tracker</title>
    <link rel="stylesheet" href="theme.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .register-card {
            width: 100%;
            max-width: 500px;
            padding: 40px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--text-color);
        }
        input, select {
            width: 100%;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        .footer-links {
            text-align: center;
            margin-top: 25px;
            color: var(--text-color);
            font-size: 0.9rem;
        }
        .footer-links a {
            color: var(--accent-color, #3498db);
            text-decoration: none;
            font-weight: bold;
        }
        header h1, header p {
            color: var(--text-color);
        }
    </style>
</head>
<body class="gradient-active">

    <main class="register-card card">
        <header style="text-align: center; margin-bottom: 30px;">
            <h1 style="margin: 0;">Student Registration</h1>
            <p style="opacity: 0.8;">Create your account to join your section.</p>
        </header>

        <form action="register_process.php" method="POST">
            
            <label for="student_id_number">Student ID Number</label>
            <input type="text" id="student_id_number" name="student_id_number" required placeholder="e.g., 2024-0001">

            <label for="username">Full Name / Username</label>
            <input type="text" id="username" name="username" required placeholder="Enter your full name">

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required placeholder="yourname@example.com">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="8" required placeholder="Minimum 8 characters">

            <label for="section_id">Select Your Section:</label>
            <select id="section_id" name="section_id" required>
                <option value="" disabled selected>-- Choose Section --</option>
                <?php
                    require 'db.php';
                    try {
                        // Order by grade level then name for better organization
                        $stmt = $pdo->query("SELECT id, section_name, grade_level FROM sections ORDER BY grade_level ASC, section_name ASC");
                        while ($row = $stmt->fetch()) {
                            echo "<option value='{$row['id']}'>Grade {$row['grade_level']} - {$row['section_name']}</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<option value=''>Error loading sections</option>";
                    }
                ?>
            </select>

            <button type="submit" style="width: 100%; margin-top: 10px;">Register as Student</button>
        </form>

        <div class="footer-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
            <p style="margin-top: 10px;"><a href="index.php" style="opacity: 0.7;">← Back to Home</a></p>
        </div>
    </main>

    <script src="theme.js"></script>
</body>
</html>