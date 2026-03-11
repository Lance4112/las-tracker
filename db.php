<?php
// Retrieve Railway environment variables
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: 3306; // Default to 3306 if port isn't set

// Connect using the variables
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    // This will tell you EXACTLY why it failed in your Railway logs
    die("Database Connection Error: " . mysqli_connect_error());
}
?>







