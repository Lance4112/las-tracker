<?php
// Force error reporting to show on the screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

echo "Attempting to connect to: $host on port $port...<br>";

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("CONNECTION FAILED: " . mysqli_connect_error());
} else {
    echo "SUCCESS! Connected to database.";
}
?>






