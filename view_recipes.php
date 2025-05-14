<?php
// Start the session and include the database connection
session_start();
if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit; // Make sure the script stops after redirecting
}
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Check if the user is an admin


include('db.connect.php'); // Make sure your database connection is correct

// Fetch all recipes from the database
$query = "SELECT name, cuisine, description FROM recipes where approval = TRUE order by approval_date asc";
$result = mysqli_query($conn, $query);

// Check if there are any recipes
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if the user is logged in (general user or admin)
$is_logged_in = isset($_SESSION['user_role']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMeal - All Recipes</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        // Function to open and close the modal
        function toggleModal() {
            document.getElementById('recipe-modal').classList.toggle('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <header class="bg-orange-500 text-white text-center py-8">
        <h1 class="text-4xl font-bold">InstaMeal</h1>
        <p class="text-lg mt-2">Browse through our latest recipes!</p>
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
        </ul>
    </nav>
    

    <!-- View All Recipes Section -->
    <section id="view-all-recipes-section" class="mt-12 flex flex-col items-center gap-8">
        <h2 class="text-2xl font-semibold text-center">All Recipes</h2>
        <div class="w-full max-w-5xl overflow-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-orange-500 text-white">
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Cuisine</th>
                        <th class="p-4 text-left">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="p-4"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($row['cuisine']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($row['description']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Suggest Recipe Button (visible for logged-in users only) -->
    <section class="mt-12 flex justify-center">
        <button onclick="toggleModal()" class="bg-orange-500 text-white p-3 rounded-lg shadow-md hover:bg-orange-600 focus:outline-none">
            Suggest a Recipe
        </button>
    </section>

    <!-- Modal for Suggesting a Recipe -->
    <div id="recipe-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-2xl font-semibold text-center mb-4">Suggest a Recipe</h2>
            <form method="POST" action="add_recipe.php" class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-700">Recipe Name:</label>
                    <input type="text" id="name" name="name" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="cuisine" class="block text-gray-700">Cuisine:</label>
                    <input type="text" id="cuisine" name="cuisine" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="description" class="block text-gray-700">Description:</label>
                    <textarea id="description" name="description" required class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="flex justify-between">
                    <button type="button" onclick="toggleModal()" class="bg-gray-400 text-white p-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-orange-500 text-white p-2 rounded-lg">Submit Recipe</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="text-center py-6 mt-12 bg-gray-200">
        <p>Made with ❤️ by InstaMeal Team</p>
    </footer>

    <script src="assets/js/script.js"></script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
