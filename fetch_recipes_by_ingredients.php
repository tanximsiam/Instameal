<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Enable error reporting

// Include the database connection
require_once 'db.connect.php';

// Get the input data (ingredients) from the POST request
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    die('Invalid JSON input');
}

$ingredients = $inputData['ingredients'];  // Ingredients string entered by the user

// Ensure the ingredients are lowercased for case-insensitive matching
$ingredientsArray = explode(',', $ingredients);  // Split the input ingredients by commas
$ingredientsArray = array_map('trim', $ingredientsArray);  // Remove any extra spaces

// Create placeholders for the prepared statement (one for each ingredient)
$placeholders = implode(',', array_fill(0, count($ingredientsArray), '?'));


// SQL Query: Use the user-provided ingredients dynamically
$sql = "
    SELECT r.id, r.name, r.description
    FROM recipes r
    JOIN recipe_ingredients ri ON r.id = ri.recipe_id
    JOIN ingredients i ON ri.ingredient_id = i.id
    WHERE r.approval = TRUE
    GROUP BY r.id, r.name, r.description
    HAVING COUNT(DISTINCT i.id) = " . count($ingredientsArray) . " 
    AND NOT EXISTS (
        SELECT 1
        FROM recipe_ingredients ri2
        JOIN ingredients i2 ON ri2.ingredient_id = i2.id
        WHERE ri2.recipe_id = r.id
        AND LOWER(i2.name) NOT IN ('" . implode("','", array_map('strtolower', $ingredientsArray)) . "') 
    )
";


// Prepare the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('MySQL prepare error: ' . $conn->error);  // Error handling
}

// // Bind the parameters for each ingredient search term and the count of ingredients
// $params = [];
// foreach ($ingredientsArray as $ingredient) {
//     $params[] = strtolower($ingredient);  // Convert each ingredient to lowercase for case-insensitive matching
// }
// $params[] = count($ingredientsArray);  // We are matching the exact number of ingredients

// // Bind the parameters dynamically
// $typeStr = str_repeat('s', count($ingredientsArray)) . 'i';
// $stmt->bind_param($typeStr, ...$params);

// Execute the query
if (!$stmt->execute()) {
    die('Query execution error: ' . $stmt->error);  // Error handling
}

// Use get_result() to fetch the result rows
$result = $stmt->get_result();
if (!$result) {
    die('Error fetching results: ' . $stmt->error);
}

// Initialize an array to store the final matched recipes
$recipes = [];
while ($row = $result->fetch_assoc()) {
    $recipes[] = $row;  // Add each matched recipe to the final result
}

// Return the results as JSON (this is the only output)
header('Content-Type: application/json');
echo json_encode($recipes);

// Close the database connection
$stmt->close();
$conn->close();
?>
