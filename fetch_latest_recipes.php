<?php
// Include the database connection
require_once 'db.connect.php';

// Fetch the 4 latest approved recipes, their total calories, and their ingredients
$sql = "
    SELECT r.id, r.name, r.description, 
           GROUP_CONCAT(i.name ORDER BY i.name) AS ingredients, 
           SUM(ri.quantity * i.calories) AS total_calories
    FROM recipes r
    JOIN recipe_ingredients ri ON r.id = ri.recipe_id
    JOIN ingredients i ON ri.ingredient_id = i.id
    WHERE r.approval = TRUE
    GROUP BY r.id
    ORDER BY r.approval_date DESC
    LIMIT 4";  // Limit to 4 latest recipes

$result = $conn->query($sql);
$latestRecipes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Split the comma-separated ingredients list into an array
        $ingredientsList = explode(',', $row['ingredients']);
        // Clean up any extra spaces and add it as an array of ingredients
        $row['ingredients'] = array_map('trim', $ingredientsList);
        
        // Add the recipe data to the array
        $latestRecipes[] = $row;
    }
}

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($latestRecipes);

// Close the connection
$conn->close();
?>
