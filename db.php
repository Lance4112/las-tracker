<?php
// Match the exact names from your variables tab in image_3e4237.png
$conn = mysqli_connect(
    getenv('MYSQLHOST'), 
    getenv('MYSQLUSER'), 
    getenv('MYSQLPASSWORD'), 
    getenv('MYSQLDATABASE'), // No underscore, matching image_3e4237.png
    getenv('MYSQLPORT')      // This will be 3306 internally
);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>





