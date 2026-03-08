<?php
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

// Connect using the variables Railway provided
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    // This will help you see if the connection is the problem
    error_log("Connection failed: " . mysqli_connect_error());
    die("Database Connection Error");
}
?>

