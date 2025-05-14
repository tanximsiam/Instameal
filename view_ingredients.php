<?php
// Start the session and include the database connection
session_start();
if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit;
}
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Check if the user is an admin

include('db.connect.php'); // Ensure your database connection is correct

// Process the ingredient deletion request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $ingredient_id = intval($_GET['delete_id']);

    // Check if the ingredient is used in any recipe
    $check_query = "SELECT * FROM recipe_ingredients WHERE ingredient_id = $ingredient_id LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Remove mapping first before deleting this ingredient.');</script>";
    } else {
        // Proceed to delete the ingredient
        $delete_query = "DELETE FROM ingredients WHERE id = $ingredient_id";
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>alert('Ingredient deleted successfully.'); window.location.href='view_ingredients.php';</script>";
        } else {
            echo "<script>alert('Error deleting ingredient: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// Fetch all ingredients for a specific recipe
$query = "SELECT id, name, unit, calories FROM ingredients";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMeal - View Ingredients</title>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <header class="bg-orange-500 text-white text-center py-8">
        <h1 class="text-4xl font-bold">InstaMeal</h1>
        <p class="text-lg mt-2">View Ingredients for Recipe</p>
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

    <section class="mt-12 flex flex-col items-center gap-8 w-full">
        <h2 class="text-2xl font-semibold text-center">Ingredients</h2>

        <!-- Add Ingredient Button -->
        <?php if ($is_admin): ?>
            <button id="toggle-add-ingredient" class="bg-orange-500 text-white p-2 rounded-lg shadow-md hover:bg-orange-600">
                Add New Ingredient
            </button>
        <?php endif; ?>

        <!-- Add Ingredient Form (Initially Hidden) -->
        <div id="add-ingredient-form" class="bg-white p-6 rounded-lg shadow-md w-full max-w-5xl mt-4 hidden">
            <form method="POST" action="add_ingredient.php">
                <h3 class="text-xl font-semibold mb-4">Add New Ingredient</h3>
                <div class="mb-4">
                    <label for="ingredient_name" class="block text-gray-700">Ingredient Name:</label>
                    <input type="text" id="ingredient_name" name="ingredient_name" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="unit" class="block text-gray-700">Unit:</label>
                    <input type="text" id="unit" name="unit" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="calories" class="block text-gray-700">Calories:</label>
                    <input type="number" id="calories" name="calories" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <button type="submit" name="add_ingredient" class="bg-orange-500 text-white p-2 rounded-lg shadow-md hover:bg-orange-600">
                        Add Ingredient
                    </button>
                </div>
            </form>
        </div>

        <!-- Ingredients Table -->
        <div class="w-full max-w-5xl overflow-auto px-4 mt-8">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-orange-500 text-white">
                        <th class="p-4 text-left">Ingredient Name</th>
                        <th class="p-4 text-left">Unit</th>
                        <th class="p-4 text-left">Calories</th>
                        <?php if ($is_admin): ?>
                            <th class="p-4 text-left">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="p-4"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($row['unit']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($row['calories']); ?></td>
                            <?php if ($is_admin): ?>
                            <td class="p-4">
                                <a href="view_ingredients.php?delete_id=<?php echo $row['id']; ?>" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">
                                    Delete
                                </a>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <footer class="text-center py-6 mt-12 bg-gray-200">
        <p>Made with ❤️ by InstaMeal Team</p>
    </footer>

    <script>
        // Toggle the visibility of the Add Ingredient form
        document.getElementById('toggle-add-ingredient').addEventListener('click', function() {
            const form = document.getElementById('add-ingredient-form');
            form.classList.toggle('hidden');
        });
    </script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
