<?php
session_start();

if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true) {
    header("Location: index.php");
    exit;
}

include('db.connect.php');
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// Handle approval
if ($is_admin && isset($_GET['approve'])) {
    $recipe_id = intval($_GET['approve']);

    // Check if recipe ID exists in the recipe_ingredients table
    $check_query = "SELECT recipe_id FROM recipe_ingredients WHERE recipe_id = $recipe_id LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);

    // If recipe ID does not exist in the recipe_ingredients table
    if (mysqli_num_rows($check_result) === 0) {
        // Display error message (can be shown on the page or a redirect with a query parameter)
        echo "<script>alert('Unmapped recipe cannot be approved. Map ingredients first.');</script>";
    } else {
        // If mapped, proceed with the approval
        $approval_date = date('Y-m-d');
        mysqli_query($conn, "UPDATE recipes SET approval = 1, approval_date = '$approval_date' WHERE id = $recipe_id");
        header("Location: admin_panel.php");
        exit;
    }
}

// Handle removal
if ($is_admin && isset($_GET['remove'])) {
    $recipe_id = intval($_GET['remove']);
    mysqli_query($conn, "UPDATE recipes SET approval = 0, approval_date = NULL WHERE id = $recipe_id");
    header("Location: admin_panel.php");
    exit;
}

// Handle edit submission
if ($is_admin && isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $cuisine = mysqli_real_escape_string($conn, $_POST['cuisine']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    mysqli_query($conn, "UPDATE recipes SET name = '$name', cuisine = '$cuisine', description = '$description' WHERE id = $edit_id");
    header("Location: admin_panel.php");
    exit;
}

// Queries
$suggested_result = mysqli_query($conn, "SELECT id, name, cuisine, description FROM recipes WHERE approval = FALSE ORDER BY id ASC");
$approved_result = mysqli_query($conn, "SELECT id, name, cuisine, description FROM recipes WHERE approval = TRUE ORDER BY approval_date DESC");

$editing_id = isset($_GET['edit']) ? intval($_GET['edit']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - InstaMeal</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<header class="bg-orange-500 text-white text-center py-6">
    <h1 class="text-3xl font-bold">InstaMeal Admin Panel</h1>
    <p class="mt-1 text-lg">Manage Recipes</p>
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

<main class="container mx-auto px-4 py-8">
<?php if ($is_admin): ?>

    <!-- Suggested Recipes -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Suggested Recipes</h2>
        <?php if (mysqli_num_rows($suggested_result) > 0): ?>
        <table class="min-w-full bg-white shadow border border-gray-200 rounded-lg">
            <thead class="bg-orange-500 text-white">
                <tr>
                    <th class="p-4">Name</th>
                    <th class="p-4">Cuisine</th>
                    <th class="p-4">Description</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($suggested_result)): ?>
                <tr class="hover:bg-gray-50 border-t">
                    <td class="p-4"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($row['cuisine']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($row['description']) ?></td>
                    <td class="p-4">
                        <a href="?approve=<?= $row['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Approve</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-gray-600">No pending recipes.</p>
        <?php endif; ?>
    </section>

    <!-- Approved Recipes with Edit -->
    <section>
        <h2 class="text-2xl font-semibold mb-4">Approved Recipes</h2>
        <?php if (mysqli_num_rows($approved_result) > 0): ?>
        <table class="min-w-full bg-white shadow border border-gray-200 rounded-lg">
            <thead class="bg-orange-500 text-white">
                <tr>
                    <th class="p-4">Name</th>
                    <th class="p-4">Cuisine</th>
                    <th class="p-4">Description</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($approved_result)): ?>
                <tr class="hover:bg-gray-50 border-t">
                    <?php if ($editing_id === intval($row['id'])): ?>
                    <form method="POST">
                        <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                        <td class="p-2"><input name="name" value="<?= htmlspecialchars($row['name']) ?>" class="w-full border rounded p-1"></td>
                        <td class="p-2"><input name="cuisine" value="<?= htmlspecialchars($row['cuisine']) ?>" class="w-full border rounded p-1"></td>
                        <td class="p-2"><textarea name="description" class="w-full border rounded p-1"><?= htmlspecialchars($row['description']) ?></textarea></td>
                        <td class="p-2 flex gap-2">
                            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Save</button>
                            <a href="admin_panel.php" class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500">Cancel</a>
                        </td>
                    </form>
                    <?php else: ?>
                    <td class="p-4"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($row['cuisine']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($row['description']) ?></td>
                    <td class="p-4 flex gap-2">
                        <a href="?edit=<?= $row['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>
                        <a href="?remove=<?= $row['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Remove</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-gray-600">No approved recipes available.</p>
        <?php endif; ?>
    </section>

<?php else: ?>
    <p class="text-red-500 font-semibold">You do not have permission to view this page.</p>
<?php endif; ?>
</main>

<footer class="text-center py-6 mt-12 bg-gray-200">
    <p>Made with ❤️ by InstaMeal Team</p>
</footer>

</body>
</html>

<?php mysqli_close($conn); ?>
