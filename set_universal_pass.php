<?php
require 'db.php';

// The password you want everyone to have
$universal_password = "password123";
$new_hash = password_hash($universal_password, PASSWORD_DEFAULT);

try {
    // This SQL command updates EVERY user in the table
    $sql = "UPDATE users SET password_hash = ?";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([$new_hash])) {
        $count = $stmt->rowCount();
        echo "<h2>Success!</h2>";
        echo "Password for all <b>$count</b> users has been reset to: <b>$universal_password</b>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>