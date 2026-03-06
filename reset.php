<?php
require 'db.php';

$new_password = 'password123';
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = 'teacher_smith'");
    $stmt->execute([$new_hash]);
    echo "Success! The password for <strong>teacher_smith</strong> is now <strong>password123</strong>";
    echo "<br><a href='login.php'>Go to Login</a>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>