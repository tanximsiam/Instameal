<?php
// Include the database connection
require_once 'db.connect.php';

// Fetch the 3 latest approved recipes from the database
$sql = "SELECT id, name, description
        FROM recipes
        WHERE approval = TRUE
        ORDER BY approval_date DESC
        LIMIT 3";  // Limit to 3 latest recipes

$result = $conn->query($sql);
$latestRecipes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $latestRecipes[] = $row;
    }
}

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($latestRecipes);

// Close the connection
$conn->close();
?>
