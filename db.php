<?php
// Use the internal host to connect within Railway's network
$conn = mysqli_connect(
    getenv('MYSQLHOST'),     // Points to mysql.railway.internal
    getenv('MYSQLUSER'),     // Points to root
    getenv('MYSQLPASSWORD'), 
    getenv('MYSQLDATABASE'), // Points to railway
    getenv('MYSQLPORT')      // Points to 3306
);

if (!$conn) {
    die("Database Connection Error");
}
?>






