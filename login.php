<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | LAS Tracker</title>
    <link rel="stylesheet" href="theme.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        /* .login-card will now inherit Glassmorphism from theme.css via the .card class */
        .login-card { 
            width: 100%;
            max-width: 400px; 
            padding: 40px;
        }
        .btn-group { 
            display: flex; 
            flex-direction: column; 
            gap: 12px; 
            margin-top: 20px; 
        }
        .error-msg { 
            color: #ffffff; 
            background: #e74c3c; 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            font-size: 0.9rem; 
            text-align: center;
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--text-color);
        }
        input {
            width: 100%;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
        .register-link {
            text-align: center;
            margin-top: 25px;
            color: var(--text-color);
            font-size: 0.95rem;
        }
        .register-link a {
            color: var(--accent-color, #3498db);
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body class="gradient-active">

    <section class="login-card card">
        <h2 style="text-align: center; margin-top: 0; color: var(--text-color);">Welcome</h2>
        <p style="text-align: center; opacity: 0.7; color: var(--text-color); margin-bottom: 30px;">Please enter your details</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">❌ Invalid Username or Password</div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <input type="hidden" name="login_attempt" value="1">

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username" placeholder="Enter your username">
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            
            <div class="btn-group">
                <button type="submit">Sign In</button>

                <a href="index.php" style="text-decoration: none;">
                    <button type="button" style="background-color: transparent !important; border: 1px solid var(--border-color) !important; color: var(--text-color) !important; width: 100%;">
                        ← Back to Home
                    </button>
                </a>
            </div>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </section>

    <script src="theme.js"></script>
</body>
</html>