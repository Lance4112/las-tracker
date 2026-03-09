<?php
// Matching your exact variables from image_3e4237.png
$host = getenv('MYSQLHOST');      // Points to mysql.railway.internal
$user = getenv('MYSQLUSER');      // Points to root
$pass = getenv('MYSQLPASSWORD'); 
$db   = getenv('MYSQLDATABASE');  // Matches your new no-underscore setting
$port = getenv('MYSQLPORT');      // Points to 3306

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    // This helps us see the real error if it fails
    error_log("Connection failed: " . mysqli_connect_error());
    die("Database Connection Error");
}
?>




