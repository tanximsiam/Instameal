<?php
// Include the database connection
require_once 'db.connect.php';

// Fetch the 4 latest approved recipes and their total calories
$sql = "
    SELECT r.id, r.name, r.description, 
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
        $latestRecipes[] = $row;
    }
}

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($latestRecipes);

// Close the connection
$conn->close();
?>