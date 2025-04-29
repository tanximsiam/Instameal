<?php
// Start the session
session_start();

// Destroy the session to log the user out
session_unset();  // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to the login page
header("Location: index.php");
exit; // Ensure no further code is executed after the redirect
?>