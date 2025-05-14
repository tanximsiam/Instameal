<?php
// Start the session
session_start();

// Check if the user is authenticated and has the admin role
if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true || $_SESSION['role'] !== 'admin') {
    // Redirect to home page or show an access denied message
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Mapper - Admin Only</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <header class="bg-orange-500 text-white text-center py-8">
        <h1 class="text-4xl font-bold">Recipe Mapper - Admin Panel</h1>
        <button onclick="window.location.href='logout.php';" class="absolute top-5 right-5 bg-red-500 text-black p-2 rounded-lg hover:bg-white focus:outline-none z-10">
            Logout
        </button>
        <button onclick="window.location.href='home.php';" class="absolute top-5 left-5 bg-red-500 text-black p-2 rounded-lg hover:bg-white focus:outline-none z-10">
            Back to Home
        </button>
    </header>

    <!-- Recipe Search Bar Section -->
    <section id="search-recipes-section" class="w-1/2 mt-12 flex flex-wrap self-center gap-4 relative">
        <div class="relative w-full">
            <h1 class="text-xl font-semibold mb-4">Recipe Name</h1>
            <!-- Recipe input field for mapping -->
            <input type="text" id="recipe-input" placeholder="Search for recipes..." 
                class="w-full self-center p-3 border-2 border-orange-500 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-orange-500">

            <!-- Suggestions box (hidden initially) -->
            <div id="recipe-suggestions-box" class="absolute top-full left-0 w-full mt-1 bg-white border-2 border-gray-300 rounded-lg shadow-md max-h-60 overflow-y-auto z-10 hidden">
                <!-- Recipe suggestions will appear here -->
            </div>
        </div>
    </section>

    <!-- Ingredient and Quantity Input Fields -->
    <section id="ingredient-section" class="w-1/2 mt-12 flex flex-wrap self-center gap-4">
        <h2 class="text-xl font-semibold mb-4">Ingredients</h2>

        <div id="ingredient-container" class="w-full ">
            <!-- First ingredient pair -->
            <div class="ingredient-pair flex gap-4 mb-4">
                <input type="text" name="ingredients[]" placeholder="Ingredient" class="grow-10 p-2 border-2 border-gray-300 rounded-md" required>
                <input type="number" name="quantities[]" placeholder="Quantity" class="grow-2 p-2 border-2 border-gray-300 rounded-md" required>
                <button type="button" class="remove-button grow-1 bg-red-500 text-white p-2 rounded-md hover:bg-red-600 focus:outline-none" onclick="removeIngredientPair(this)">-</button>
            </div>
        </div>
    </section>
    <!-- Submit Button for the form -->
    <section class="text-center mt-4">
        <button type="button" id="submit-map-btn" class="bg-orange-500 text-white p-3 rounded-lg mt-4 hover:bg-orange-600 focus:outline-none">
            Submit Map
        </button>
    </section>

    <footer class="text-center py-6 mt-12 bg-gray-200">
        <p>Made with ❤️ by InstaMeal Team</p>
    </footer>

    <!-- Include your external JavaScript -->
    <script>
        // Function to add a new ingredient pair dynamically
        function addIngredientPair() {
            const ingredientContainer = document.getElementById('ingredient-container');
            const lastPair = ingredientContainer.querySelector('.ingredient-pair:last-child');
            
            // Only add new field if the last one has been filled
            const lastIngredientInput = lastPair.querySelector('input[type="text"]');
            const lastQuantityInput = lastPair.querySelector('input[type="number"]');
            
            if (lastIngredientInput.value !== "" && lastQuantityInput.value !== "") {
                const newPair = document.createElement('div');
                newPair.classList.add('ingredient-pair', 'flex', 'gap-4', 'mb-4');
                newPair.innerHTML = `
                    <input type="text" name="ingredients[]" placeholder="Ingredient" class="grow-10 p-2 border-2 border-gray-300 rounded-md" required>
                    <input type="number" name="quantities[]" placeholder="Quantity" class="grow-2 p-2 border-2 border-gray-300 rounded-md" required>
                    <button type="button" class="remove-button grow-1 bg-red-500 text-white p-2 rounded-md hover:bg-red-600 focus:outline-none" onclick="removeIngredientPair(this)">-</button>
                `;
                ingredientContainer.appendChild(newPair);
            }
        }

        // Function to remove an ingredient pair or clear the last one
        function removeIngredientPair(button) {
            const ingredientContainer = document.getElementById('ingredient-container');
            const pair = button.closest('.ingredient-pair');

            const ingredientPairs = document.querySelectorAll('.ingredient-pair');
            if (ingredientPairs.length > 1) {
                // Remove the pair if there are more than one
                pair.remove();
            } else {
                // Clear the fields if it's the last pair
                const inputs = pair.querySelectorAll('input');
                inputs.forEach(input => input.value = "");
            }
        }

        // Automatically add a new ingredient pair when the last input is filled
        document.querySelector('#ingredient-container').addEventListener('input', function(e) {
            if (e.target.tagName === 'INPUT') {
                const ingredientPairs = document.querySelectorAll('.ingredient-pair');
                const lastPair = ingredientPairs[ingredientPairs.length - 1];
                const lastIngredientInput = lastPair.querySelector('input[type="text"]');
                const lastQuantityInput = lastPair.querySelector('input[type="number"]');
                
                // Check if the last pair is filled
                if (lastIngredientInput.value && lastQuantityInput.value) {
                    addIngredientPair();
                }
            }
        });
    // Submit Button to submit the recipe and ingredients
    document.getElementById('submit-map-btn').addEventListener('click', function() {
        const recipeName = document.getElementById('recipe-input').value;
        const ingredients = [];
        const quantities = [];

        // Collect ingredients and quantities from the input fields, ignoring the last empty pair
        let valid = true;  // Flag to track validation status

        document.querySelectorAll('input[name="ingredients[]"]').forEach((input, index) => {
            const ingredientValue = input.value;
            const quantityValue = document.querySelectorAll('input[name="quantities[]"]')[index].value;

            // Check if the current ingredient and quantity pair is not empty
            if (ingredientValue.trim() !== "" && quantityValue.trim() !== "") {
                ingredients.push(ingredientValue);
                quantities.push(quantityValue);
            }
        });

        // Ensure we have at least one valid ingredient before submitting
        if (ingredients.length === 0 || quantities.length === 0) {
            alert("Please fill in at least one ingredient and quantity pair.");
            return;  // Prevent form submission if no valid data
        }

        // Send the data to the backend using POST (via fetch)
        fetch('submit_recipe_mapping.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                recipe_name: recipeName,
                ingredients: ingredients,
                quantities: quantities
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                alert("Recipe and ingredients successfully submitted!");
                // Clear the input fields before submitting (reset the form fields)
                document.getElementById('recipe-input').value = '';  // Clear recipe name input field

                // Clear all ingredient and quantity fields (except the first one)
                const ingredientPairs = document.querySelectorAll('.ingredient-pair');
                ingredientPairs.forEach((pair, index) => {
                    if (index > 0) {
                        pair.remove();  // Remove all ingredient pairs except the first one
                    } else {
                        const inputs = pair.querySelectorAll('input');
                        inputs.forEach(input => input.value = "");  // Clear the first ingredient pair
                    }
                });
            })
            .catch(error => {
                console.error('Error submitting recipe:', error);
                alert("Something went wrong. Please try again.");
            });
    });

    </script>

    <script src="assets/js/script.js"></script>

</body>
</html>
