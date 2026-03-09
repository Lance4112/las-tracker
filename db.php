<?php
// These variables are automatically filled by Railway
$host = getenv('MYSQLHOST');      // This will be 'mysql.railway.internal'
$user = getenv('MYSQLUSER');      // This will be 'root'
$pass = getenv('MYSQLPASSWORD'); 
$db   = getenv('MYSQLDATABASE');  // This will be 'railway'
$port = getenv('MYSQLPORT');      // This will be '3306'

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

