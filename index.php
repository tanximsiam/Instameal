<?php
// Start the session to display any error messages
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMeal - Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-md mx-auto mt-16 p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-semibold text-center mb-6">InstaMeal - Login</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-200 text-red-700 p-2 mb-4 rounded-md"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="login.php">
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-3 mt-1 border rounded-md focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 mt-1 border rounded-md focus:outline-none" required>
            </div>

            <!-- Login Button (Submit) -->
            <button type="submit" class="w-full py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none transition duration-200 ease-in-out">Login</button>
        </form>
    </div>

</body>
</html>
