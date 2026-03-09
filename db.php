<?php
// Matching the exact names from your image_3e5155.png
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE'); // Note the underscore from your screenshot
$port = getenv('MYSQLPORT');

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    // This will show you the real error if it fails again
    die("Connection failed: " . mysqli_connect_error());
}
?>



