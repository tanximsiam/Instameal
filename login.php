<?php
// Start the session
session_start();

// Include the database connection
require_once 'db.connect.php';

// Check if the form is submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple SQL query to check if the user exists (no security checks)
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  // Note: This is not secure
    $result = mysqli_query($conn, $sql);

    // Check if the user is found
    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $user = mysqli_fetch_assoc($result);

        // Store user session data
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to home.php after successful login
        header("Location: home.php");
        exit();
    } else {
        // If login fails, show an error message
        $_SESSION['error_message'] = "Wrong username or password";
        header("Location: index.php");  // Redirect back to the login page
        exit();
    }
}
?>
