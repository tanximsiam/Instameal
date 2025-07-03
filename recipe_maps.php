<?php
// Start the session and include the database connection
session_start();
if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true || $_SESSION['role'] !== 'admin') {
    // Redirect to home page or show an access denied message
    header("Location: index.php");
    exit;
}

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Check if the user is an admin


include('db.connect.php');

// Handle search query for filtering recipes and ingredients
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Handle delete request for mapping
if (isset($_GET['remove_recipe_id']) && isset($_GET['remove_ingredient_id'])) {
    $recipe_id = intval($_GET['remove_recipe_id']);
    $ingredient_id = intval($_GET['remove_ingredient_id']);
    
    // Proceed to delete the recipe ingredient mapping
    $delete_query = "DELETE FROM recipe_ingredients WHERE recipe_id = $recipe_id AND ingredient_id = $ingredient_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Mapping removed successfully.'); window.location.href='recipe_maps.php';</script>";
    } else {
        echo "<script>alert('Error removing recipe mapping: " . mysqli_error($conn) . "');</script>";
    }
}

// Fetch all recipe mappings with an optional search filter for both recipe and ingredient names
$query = "SELECT ri.recipe_id, ri.ingredient_id, r.name AS recipe_name, i.name AS ingredient_name, ri.quantity
          FROM recipe_ingredients ri
          JOIN recipes r ON ri.recipe_id = r.id
          JOIN ingredients i ON ri.ingredient_id = i.id 
          WHERE r.name LIKE '%$search_query%' OR i.name LIKE '%$search_query%' 
          ORDER BY r.name";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Maps - Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-orange-500 text-white text-center py-8">
        <h1 class="text-4xl font-bold">InstaMeal</h1>
        <p class="text-lg mt-2">Checkout all mappings</p>
    </header>
    <nav class="bg-orange-400 text-white py-4">
        <ul class="flex justify-center space-x-8">
            <li><a href="home.php" class="hover:text-black">Home</a></li>
            <li><a href="view_recipes.php" class="hover:text-black">View Recipes</a></li>
            <li><a href="view_ingredients.php" class="hover:text-black">View Ingredients</a></li>

            <!-- Show admin-only links if the user is an admin -->
            <?php if ($is_admin): ?>
                <li><a href="recipe_maps.php" class="hover:text-black">Recipe Maps</a></li>
                <li><a href="admin_panel.php" class="hover:text-black">Admin Panel</a></li>
            <?php endif; ?>
            <li><a href="view_profile.php" class="hover:text-black">View Profile</a></li>
        </ul>
    </nav>

    <!-- Recipe Search Bar Section -->
    <section id="search-recipes-section" class="mt-12 flex justify-center items-center">
        <div class="w-full max-w-md">
            <h1 class="text-xl font-semibold mb-4 text-center">Search for Recipe</h1>
            <form method="GET" action="recipe_maps.php" class="flex flex-col items-center">
                <input type="text" name="search" id="recipe-input" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search for recipes or ingredients"
                    class="w-full p-3 border-2 border-orange-500 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-orange-500 mb-4">
                <button type="submit" class="bg-orange-500 text-white p-2 rounded-lg hover:bg-orange-600 focus:outline-none w-full">
                    Search
                </button>
            </form>
        </div>
    </section>

    <!-- Recipe Mapping Table Section -->
    <section id="recipe-mapping-section" class="mt-12 w-full max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-4 w-full">
            <h2 class="text-2xl font-semibold">Recipe Ingredient Mappings</h2>
            <a href="recipe_mapper.php" class="bg-orange-500 text-white p-2 rounded-lg hover:bg-orange-600 focus:outline-none">
                Map New
            </a>
        </div>
        
        <div class="w-full overflow-auto px-4 mt-4">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-orange-500 text-white">
                        <th class="p-4 text-left">Recipe Name</th>
                        <th class="p-4 text-left">Ingredient Name</th>
                        <th class="p-4 text-left">Quantity</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="p-4"><?php echo htmlspecialchars($row['recipe_name']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($row['ingredient_name']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td class="p-4">
                                <a href="recipe_maps.php?remove_recipe_id=<?php echo $row['recipe_id']; ?>&remove_ingredient_id=<?php echo $row['ingredient_id']; ?>" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">
                                    Remove
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
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
