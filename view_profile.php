<?php
// Start the session to display any error or success messages
session_start();

// Include the database connection
include('db.connect.php');

// Check if the user is logged in
if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user details
$query = "SELECT username, email FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error_message'] = 'Passwords do not match.';
        header('Location: view_profile.php');
        exit;
    }

    // Prepare the update query
    $update_fields = "username = '$new_username', email = '$new_email'";  // Always update username and email

    // If new password is provided, include it in the query
    if (!empty($new_password)) {
        $update_fields .= ", password = '$new_password'"; // Add password to the update fields
    }

    // Update the user's profile details
    $update_query = "UPDATE users SET $update_fields WHERE user_id = $user_id";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success_message'] = 'Profile updated successfully.';
        header('Location: view_profile.php'); // Redirect to the same page after success
        exit;
    } else {
        $_SESSION['error_message'] = 'Error updating profile. Please try again.';
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
    <title>InstaMeal - View Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-md mx-auto mt-16 p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-semibold text-center mb-6">InstaMeal - View Profile</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-200 text-red-700 p-2 mb-4 rounded-md"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-200 text-green-700 p-2 mb-4 rounded-md"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <!-- Profile Form -->
        <form method="POST" action="view_profile.php">
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full p-3 mt-1 border rounded-md focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full p-3 mt-1 border rounded-md focus:outline-none" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold text-gray-700">New Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 mt-1 border rounded-md focus:outline-none">
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-semibold text-gray-700">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full p-3 mt-1 border rounded-md focus:outline-none">
            </div>

            <!-- Save Changes Button -->
            <button type="submit" class="w-full py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none transition duration-200 ease-in-out">Save Changes</button>
        </form>
    </div>

</body>
</html>
