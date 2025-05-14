<?php
// Start session for error handling and validation
session_start();

// Include the database connection
include('db.connect.php');

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = 'Passwords do not match.';
        header('Location: register.php');
        exit;
    }

    // Check if email is in valid format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Invalid email format.';
        header('Location: register.php');
        exit;
    }

    // Insert user into the database
    $query = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', 'general')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = 'Registration successful! Please login.';
        header('Location: index.php'); // Redirect to login page
        exit;
    } else {
        $_SESSION['error_message'] = 'Error during registration. Please try again.';
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMeal - Register</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-md mx-auto mt-16 p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-semibold text-center mb-6">InstaMeal - Register</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-200 text-red-700 p-2 mb-4 rounded-md"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-200 text-green-700 p-2 mb-4 rounded-md"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="POST" action="register.php">
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-3 mt-1 border rounded-md focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 mt-1 border rounded-md focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 mt-1 border rounded-md focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full p-3 mt-1 border rounded-md focus:outline-none" required>
            </div>

            <!-- Register Button -->
            <button type="submit" class="w-full py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none transition duration-200 ease-in-out">Register</button>
        </form>

        <!-- Link to Login page -->
        <div class="text-center mt-4">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">Already have an account? Login</a>
        </div>
    </div>

</body>
</html>
