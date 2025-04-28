<?php
// Start the session to display any error messages
session_start();
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
        <p class="text-lg mt-2">Find recipes with the ingredients you have!</p>
    </header>

    <!-- Search Bar Section -->
    <section id="search-recipes-section" class="mt-12 flex justify-center gap-4">
        <input type="text" id="ingredients-input" placeholder="Enter ingredients (e.g. chicken, tomato)"
            class="w-1/2 p-3 border-2 border-orange-500 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-orange-500">
        
        <button onclick="searchRecipes()" class="bg-orange-500 text-white p-3 rounded-lg shadow-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500">
            Search
        </button>
    </section>

    <!-- Latest Recipes Section -->
    <section class="mt-12 flex-grow">
        <h2 id="section-title" class="text-2xl font-semibold text-center mb-8">Latest Approved Recipes</h2>
        <div class="max-w-3/5 flex flex-wrap justify-center gap-6" id="recipes-list">
            <!-- Recipe cards will be inserted here by JavaScript -->
        </div>
    </section>

    <footer class="text-center py-6 mt-12 bg-gray-200">
        <p>Made with ❤️ by InstaMeal Team</p>
    </footer>

    <script src="assets/js/script.js"></script>

</body>
</html>
