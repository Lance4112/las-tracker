<?php
// Use the variables you linked from your MySQL service
$host = getenv('MYSQLHOST');
$db   = getenv('MYSQLDATABASE');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$port = getenv('MYSQLPORT');

try {
    // This is the PDO driver that was missing
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    
    // Set error mode to exception to see actual SQL errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // echo "Connected successfully!"; 
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
