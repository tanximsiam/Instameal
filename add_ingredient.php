<?php
// Start the session and include the database connection
session_start();
if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit;
}

include('db.connect.php'); // Ensure your database connection is correct

// Process form submission to add ingredient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ingredient_name'], $_POST['unit'], $_POST['calories'])) {
    $ingredient_name = mysqli_real_escape_string($conn, $_POST['ingredient_name']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $calories = intval($_POST['calories']); // Assuming calories is an integer

    // Insert the new ingredient into the ingredients table
    $query = "INSERT INTO ingredients (name, unit, calories) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $ingredient_name, $unit, $calories);

    if (mysqli_stmt_execute($stmt)) {
        // On success, redirect back to view_ingredients.php to display the updated list
        header("Location: view_ingredients.php");
        exit;
    } else {
        echo "<p>Error adding ingredient: " . mysqli_error($conn) . "</p>";
    }
}

// Close the database connection
mysqli_close($conn);
?>
