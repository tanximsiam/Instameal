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

// Log the incoming data for debugging
file_put_contents('php://stderr', "Received Ingredients: " . json_encode($inputData) . "\n");

// Get the ingredients and process them
$ingredients = $inputData['ingredients'];  // Ingredients string entered by the user
$ingredientsArray = explode(',', $ingredients);  // Split the input ingredients by commas
$ingredientsArray = array_map('trim', $ingredientsArray);  // Remove any extra spaces
$ingredientsList = implode("','", array_map('strtolower', $ingredientsArray));

// Log the ingredients list for debugging
file_put_contents('php://stderr', "Ingredients List for Query: '$ingredientsList'\n");


// --- Exact Matches Query ---
$exactMatchQuery = "
    SELECT r.id, r.name, r.description, 
           GROUP_CONCAT(i.name ORDER BY i.name) AS ingredients, 
           SUM(ri.quantity * i.calories) AS total_calories
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
        AND LOWER(i2.name) NOT IN ('$ingredientsList')
    )
    ORDER BY r.approval_date DESC;
";

// Log the Exact Match Query for debugging
file_put_contents('php://stderr', "Exact Match Query: $exactMatchQuery\n");

// Execute the exact match query
$exactMatchResult = $conn->query($exactMatchQuery);
if (!$exactMatchResult) {
    die('Exact Match Query Error: ' . $conn->error);
}

// Store the exact match recipes
$exactMatches = [];
$exactRecipeIds = [];
while ($row = $exactMatchResult->fetch_assoc()) {
    $eachingredientsList = explode(',', $row['ingredients']);
    $row['ingredients'] = array_map('trim', $eachingredientsList);
    $exactMatches[] = $row;
    $exactRecipeIds[] = $row['id'];  // Store exact recipe IDs to exclude later
}

// Log the exact matches results
file_put_contents('php://stderr', "Exact Matches Found: " . json_encode($exactMatches) . "\n");


// --- Suggested Matches Query ---

// Convert exactRecipeIds array into a comma-separated string for the SQL query
if (!empty($exactRecipeIds)) {
    $exactRecipeIdsList = implode(",", $exactRecipeIds);  // Convert array to comma-separated string
} else {
    $exactRecipeIdsList = '';  // If there are no exact matches, leave the list empty
}

// --- Modify Suggested Matches Query to use the exactRecipeIdsList ---
$suggestedMatchQuery = "
    SELECT r.id, r.name, r.description, 
           GROUP_CONCAT(i.name ORDER BY i.name) AS ingredients, 
           SUM(ri.quantity * i.calories) AS total_calories
    FROM recipes r
    LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id
    LEFT JOIN ingredients i ON ri.ingredient_id = i.id
    WHERE r.approval = TRUE
    AND LOWER(i.name) IN ('$ingredientsList')  -- Search ingredients
";

// If exactRecipeIdsList is not empty, add the NOT IN condition to exclude exact matches
if (!empty($exactRecipeIdsList)) {
    $suggestedMatchQuery .= " AND r.id NOT IN ($exactRecipeIdsList)";  // Exclude exact matches
}

$suggestedMatchQuery .= "
    GROUP BY r.id, r.name, r.description
    ORDER BY r.approval_date DESC;
";

// Log the Suggested Match Query for debugging
file_put_contents('php://stderr', "Suggested Match Query: $suggestedMatchQuery\n");

// Execute the suggested match query
$suggestedMatchResult = $conn->query($suggestedMatchQuery);
if (!$suggestedMatchResult) {
    die('Suggested Match Query Error: ' . $conn->error);
}

// Store the suggested recipes
$suggestedMatches = [];
$suggestedRecipeIds = [];
while ($row = $suggestedMatchResult->fetch_assoc()) {
    $ingredientsList = explode(',', $row['ingredients']);
    $row['ingredients'] = array_map('trim', $ingredientsList);
    $suggestedMatches[] = $row;
    $suggestedRecipeIds[] = $row['id'];  // Store suggested recipe IDs
}

// Log the suggested matches results
file_put_contents('php://stderr', "Suggested Matches Found: " . json_encode($suggestedMatches) . "\n");

// --- Fetch All Ingredients for Suggested Recipes ---
if (!empty($suggestedRecipeIds)) {
    $suggestedRecipesIdsList = implode(",", $suggestedRecipeIds);

    $allIngredientsQuery = "
        SELECT r.id, GROUP_CONCAT(i.name ORDER BY i.name) AS all_ingredients
        FROM recipes r
        LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id
        LEFT JOIN ingredients i ON ri.ingredient_id = i.id
        WHERE r.id IN ($suggestedRecipesIdsList)
        GROUP BY r.id;
    ";

    $allIngredientsResult = $conn->query($allIngredientsQuery);
    if (!$allIngredientsResult) {
        die('All Ingredients Query Error: ' . $conn->error);
    }

    // Merge all ingredients into the 'ingredients' key for suggested recipes
    while ($row = $allIngredientsResult->fetch_assoc()) {
        foreach ($suggestedMatches as &$suggestedRecipe) {
            if ($suggestedRecipe['id'] == $row['id']) {
                // Update the 'ingredients' key with all ingredients
                $suggestedRecipe['ingredients'] = explode(',', $row['all_ingredients']);
                break;
            }
        }
    }
}


// Return the results as JSON (two separate arrays: exactMatches and suggestedMatches)
header('Content-Type: application/json');
echo json_encode([
    'exactMatches' => $exactMatches,
    'suggestedMatches' => $suggestedMatches
]);

// Close the database connection
$conn->close();
?>
