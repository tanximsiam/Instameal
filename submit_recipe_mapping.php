<?php
include('db.connect.php'); 


$data = json_decode(file_get_contents('php://input'), true);


if (isset($data['recipe_name'], $data['ingredients'], $data['quantities'])) {
    $recipe_name = $data['recipe_name'];
    $ingredients = $data['ingredients'];
    $quantities = $data['quantities'];

    $query = "SELECT id FROM recipes WHERE name = '$recipe_name' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "Error: Recipe '$recipe_name' not found in the recipes table.";
        exit;
    }

    $recipe = mysqli_fetch_assoc($result);
    $recipe_id = $recipe['id'];
    foreach ($ingredients as $index => $ingredient_name) {
        $ingredient_name = mysqli_real_escape_string($conn, $ingredient_name);
        $quantity = mysqli_real_escape_string($conn, $quantities[$index]);

        $ingredient_query = "SELECT id FROM ingredients WHERE name = '$ingredient_name' LIMIT 1";
        $ingredient_result = mysqli_query($conn, $ingredient_query);

        if (!$ingredient_result || mysqli_num_rows($ingredient_result) === 0) {
            echo "Error: Ingredient '$ingredient_name' not found in the ingredients table.";
            exit;
        }


        $ingredient = mysqli_fetch_assoc($ingredient_result);
        $ingredient_id = $ingredient['id'];

        $insert_query = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity)
                         VALUES ('$recipe_id', '$ingredient_id', '$quantity')";
        $insert_result = mysqli_query($conn, $insert_query);

        if (!$insert_result) {
            echo "Error inserting ingredient: " . mysqli_error($conn);
            exit;
        }
    }

    echo json_encode(["message" => "Recipe and ingredients mapped successfully!"]);

} else {
    echo "Error: Missing required data (recipe_name, ingredients, quantities).";
}
?>