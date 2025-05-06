<?php
// Start the session to display any error messages
session_start();

if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit; // Make sure the script stops after redirecting
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMeal - Home</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <header class="bg-orange-500 text-white text-center py-8">
        <h1 class="text-4xl font-bold">InstaMeal</h1>
        <button onclick="window.location.href='logout.php';" class="absolute top-5 right-5 bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 focus:outline-none z-10">
            Logout
        </button>
        <p class="text-lg mt-2">Find recipes with the ingredients you have!</p>
    </header>
    
    <!-- Search Bar Section -->
    <section id="search-recipes-section" class="w-1/2 mt-12 flex flex-wrap self-center gap-4 relative">
        <div class="relative w-full">
            <!-- Ingredient input field -->
            <input type="text" id="ingredients-input" placeholder="Enter ingredients (e.g. chicken, tomato)"
                class="w-full self-center p-3 border-2 border-orange-500 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-orange-500">

            <!-- Suggestions box (hidden initially) -->
            <div id="suggestions-box" class="absolute top-full left-0 w-full mt-1 bg-white border-2 border-gray-300 rounded-lg shadow-md max-h-60 overflow-y-auto z-10 hidden">
            </div>
        </div>

        <!-- Your Ingredients Section (Initially Hidden) -->
        <div id="selected-ingredients-section" class="w-full flex flex-row mt-4 gap-4 content-start hidden">
            <div class="grow">
                <p id="selected-ingredients-text" class="font-semibold">Your ingredients: </p>
                <div id="selected-ingredients" class="flex flex-wrap gap-4 mt-2 self-start"></div> <!-- Will hold selected ingredient tags -->
            </div>
            <!-- Search Recipes Button (Initially Disabled) -->
            <button id="search-button" onclick="searchRecipes()" class="ml-auto h-12 w-48 bg-orange-500 text-white p-3 rounded-lg shadow-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 items-center">
                Search Recipes
            </button>
        </div>
    </section>

        

    <!-- Latest Recipes Section -->
    <section class="mt-12 flex-grow">
        <h2 id="section-title" class="text-2xl font-semibold text-center mb-8">Latest Approved Recipes</h2>
        <div id="recipes-list" class="max-w-3/5 flex flex-wrap justify-center gap-6" >
            <!-- Recipe cards will be inserted here by JavaScript -->
        </div>
    </section>

    <footer class="text-center py-6 mt-12 bg-gray-200">
        <p>Made with ❤️ by InstaMeal Team</p>
    </footer>

    <script src="assets/js/script.js"></script>

</body>
</html>
