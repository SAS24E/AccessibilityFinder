<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to homepage (or login page)
header("Location: ../../public/index.php?logged_out=1");
exit;
?>