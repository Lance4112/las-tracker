<?php
require 'db.php';

$new_password = "password123";
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Use the correct column name: password_hash
$username = 'admin'; // Change this to your actual username

$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
if($stmt->execute([$new_hash, $username])) {
    echo "<h3>Success!</h3>";
    echo "User <b>$username</b> updated. Password is now: <b>$new_password</b>";
} else {
    echo "Error updating database.";
}
?>