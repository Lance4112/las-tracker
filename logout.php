<?php
session_start();
session_unset();
session_destroy();

// Redirect to index.php instead of login.php
header("Location: index.php");
exit();
?>