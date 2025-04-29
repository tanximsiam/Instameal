<?php
// Start the session and include the database connection
session_start();
include('db.connect.php'); // Ensure the database connection is correct

// Check if the user is logged in
if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit;
}

// Get the form data (sanitize inputs)
$name = mysqli_real_escape_string($conn, $_POST['name']);
$cuisine = mysqli_real_escape_string($conn, $_POST['cuisine']);
$description = mysqli_real_escape_string($conn, $_POST['description']);

// Check if the user is an admin or general user
if ($_SESSION['role'] === 'admin') {
    // Admin: Approve the recipe and set the current date
    $approval = true;
    $approval_date = date('Y-m-d'); // Set the current date (YYYY-MM-DD)
} else {
    // General User: Set approval to false and approval_date to NULL
    $approval = false;
    $approval_date = NULL;
}

// Insert the recipe into the database
$query = "INSERT INTO recipes (name, cuisine, description, approval, approval_date)
          VALUES ('$name', '$cuisine', '$description', '$approval', '$approval_date')";
$result = mysqli_query($conn, $query);

// Check if the insertion was successful
if ($result) {
    // Success message
    echo "<p class='text-green-500 text-center'>Recipe submitted successfully!</p>";
    // Optionally redirect back to the view recipes page or homepage
    header("Location: view_recipes.php");
    exit;
} else {
    // Error message
    echo "<p class='text-red-500 text-center'>Error: " . mysqli_error($conn) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMeal - Recipe Submission</title>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <header class="bg-orange-500 text-white text-center py-8">
        <h1 class="text-4xl font-bold">InstaMeal</h1>
        <p class="text-lg mt-2">Your recipe has been submitted!</p>
    </header>

    <!-- Feedback Section (Success or Error) -->
    <section class="mt-12 flex justify-center">
        <!-- The message is displayed based on the success or failure of the submission -->
    </section>

    <footer class="text-center py-6 mt-12 bg-gray-200">
        <p>Made with ❤️ by InstaMeal Team</p>
    </footer>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>