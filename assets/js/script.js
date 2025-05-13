// Function to fetch and display the latest recipes when the page loads
function fetchLatestRecipes() {
    fetch('fetch_latest_recipes.php')  // Call the PHP endpoint for latest recipes
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();  // Parse the response as JSON
        })
        .then(data => {
            console.log("Fetched Data:", data);  // Check the response data in the console
            if (data.length > 0) {
                const recipesList = document.getElementById('matching-recipes-list');
                displayRecipes(data, recipesList);  // Call displayRecipes with the data
            } else {
                clearRecipes();  // Clear recipes if no data is found
            }
        })
        .catch(error => {
            console.error("Error fetching latest recipes:", error);
            alert("Something went wrong. Please try again later.");
        });
}


// Function to display the recipes in the HTML
// function displayRecipes(recipes) {
//     const recipesList = document.getElementById('recipes-list');
//     if (!recipesList) {
//         return;  // Exit the function if the element doesn't exist
//     }
//     recipesList.innerHTML = '';  // Clear the list before appending new recipes

//     // Set the parent container to grid layout for equal height cards, with gap and padding
//     recipesList.classList.add(
//         'flex', 'flex-wrap', 'grid', 'grid-cols-1', 'md:grid-cols-2',
//         'gap-8', 'px-8', 'py-6', 'mx-auto', 'w-full', 'justify-items-center'
//     );  // Tailwind CSS grid layout with responsive columns, gap, and padding

//     recipes.forEach(recipe => {
//         console.log("Displaying recipe:", recipe);  // Log each recipe being displayed
        
//         // Create the recipe card container
//         const recipeCard = document.createElement('div');
        
//         // Add Tailwind CSS classes for styling and ensuring equal height cards
//         recipeCard.classList.add(
//             'bg-white', 'p-6', 'rounded-2xl', 'shadow', 
//             'text-center', 'border', 'border-gray-300', 'flex', 'flex-col', 
//             'h-full', 'w-full', 'm-2'
//         );

//         // Create the recipe name element
//         const recipeName = document.createElement('h3');
//         recipeName.textContent = recipe.name;  // Set the text of the recipe name
//         recipeName.classList.add('text-xl', 'font-bold', 'text-gray-800', 'mb-2');  // Styling for name

//         recipeCard.appendChild(recipeName);

//         // Check if ingredients exist and are in the correct format
//         if (recipe.ingredients && Array.isArray(recipe.ingredients) && recipe.ingredients.length > 0) {
//             // Create the ingredients container div
//             const ingredientsContainer = document.createElement('div');
//             ingredientsContainer.classList.add('flex', 'flex-wrap', 'justify-center', 'gap-2', 'mb-4', );  // Flexbox for chips layout

//             // Loop through each ingredient and create a chip for it
//             recipe.ingredients.forEach(ingredient => {
//                 const ingredientChip = document.createElement('span');
//                 ingredientChip.textContent = ingredient;  // Set the ingredient name
//                 ingredientChip.classList.add('bg-orange-100', 'text-orange-800', 'px-2', 'py-0', 'rounded-full', 'text-sm', 'font-regular');
//                 ingredientsContainer.appendChild(ingredientChip);  // Add the chip to the ingredients container
//             });

//             // Append ingredients container under the recipe name
//             recipeCard.appendChild(ingredientsContainer);
//         }

//         // Create the recipe description element
//         const recipeDescription = document.createElement('p');
//         recipeDescription.textContent = recipe.description;  // Set the text of the recipe description
//         recipeDescription.classList.add('text-gray-600', 'mb-2', 'h-full');  // Styling for description

//         // Create the recipe calorie count element
//         const recipeCalories = document.createElement('p');
//         const totalCalories = Math.floor(recipe.total_calories);  // Ensure calories are displayed as an integer
//         recipeCalories.textContent = `Total Calories: ${totalCalories}`;  // Set the calorie text
//         recipeCalories.classList.add('text-orange-600', 'font-semibold', 'mb-2', 'text-center');  // Center aligned text

//         // Append all created elements to the recipe card
        
//         recipeCard.appendChild(recipeDescription);
//         recipeCard.appendChild(recipeCalories);  // Append the calorie part here

//         // Append the recipe card to the main list of recipes
//         recipesList.appendChild(recipeCard);
//     });
// }

// Function to render recipes in a specified container
function displayRecipes(recipes, container) {
    // Ensure the container exists
    if (!container) return;

    // Set the parent container to grid layout for equal height cards, with gap and padding
    container.classList.add(
        'flex', 'flex-wrap', 'grid', 'grid-cols-1', 'md:grid-cols-2',
        'gap-8', 'px-8', 'py-6', 'mx-auto', 'w-full', 'justify-items-center'
    );  // Tailwind CSS grid layout with responsive columns, gap, and padding

    // Iterate over each recipe and create the HTML for it
    recipes.forEach(recipe => {
        // Create the recipe card container
        const recipeCard = document.createElement('div');
        recipeCard.classList.add('bg-white', 'p-6', 'rounded-2xl', 'shadow', 'text-center', 'border', 'border-gray-300', 'flex', 'flex-col', 'w-full', 'm-2');

        // Create the recipe name element
        const recipeName = document.createElement('h3');
        recipeName.textContent = recipe.name;
        recipeName.classList.add('text-xl', 'font-bold', 'text-gray-800', 'mb-2');

        recipeCard.appendChild(recipeName);

        // Check if ingredients exist and are in the correct format
        if (recipe.ingredients && Array.isArray(recipe.ingredients) && recipe.ingredients.length > 0) {
            // Create the ingredients container div
            const ingredientsContainer = document.createElement('div');
            ingredientsContainer.classList.add('flex', 'flex-wrap', 'justify-center', 'gap-2', 'mb-4');  // Flexbox for chips layout

            // Loop through each ingredient and create a chip for it
            recipe.ingredients.forEach(ingredient => {
                const ingredientChip = document.createElement('span');
                ingredientChip.textContent = ingredient;  // Set the ingredient name
                ingredientChip.classList.add('bg-orange-100', 'text-orange-800', 'px-2', 'py-0', 'rounded-full', 'text-sm', 'font-regular');
                ingredientsContainer.appendChild(ingredientChip);  // Add the chip to the ingredients container
            });

            // Append ingredients container under the recipe name
            recipeCard.appendChild(ingredientsContainer);
        }

        // Create the recipe description element
        const recipeDescription = document.createElement('p');
        recipeDescription.textContent = recipe.description;
        recipeDescription.classList.add('text-gray-600', 'mb-2');

        // Create the recipe calorie count element
        const recipeCalories = document.createElement('p');
        const totalCalories = Math.floor(recipe.total_calories);  // Ensure calories are displayed as an integer
        recipeCalories.textContent = `Total Calories: ${totalCalories}`;
        recipeCalories.classList.add('text-orange-600', 'font-semibold', 'mb-2', 'text-center');

        // Append all created elements to the recipe card
        recipeCard.appendChild(recipeDescription);
        recipeCard.appendChild(recipeCalories);

        // Append the recipe card to the main list of recipes
        container.appendChild(recipeCard);
    });
}

// Function to update recipes section by sending data to PHP-rendered page
function updateRecipesSection(recipes) {
    // This function does not generate HTML itself. It only fetches the data
    // PHP handles the actual rendering of the cards.
    console.log('Recipes data to be sent:', recipes);
}


// Function to clear the recipes section (in case of no results)
function clearRecipes() {
    const recipesList = document.getElementById('recipes-list');
    recipesList.innerHTML = '';  // Clear the recipes list
}


// Function to fetch and display the recipes based on the search input
// function searchRecipes() {
//     const searchTerm = document.getElementById('ingredients-input').value.trim();

//     if (searchTerm === "") {
//         alert("Please enter ingredients to search!");
//         return;
//     }

//     // Prepare the data to be sent in the POST request
//     const requestData = {
//         ingredients: searchTerm // Send the ingredients string entered by the user
//     };

//     // Fetch the matching recipes based on the ingredients
//     fetch('fetch_recipes_by_ingredients.php', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify(requestData)  // Send the search term to PHP as JSON
//     })
//     .then(response => {
//         // Check if the response is OK (status 200)
//         if (!response.ok) {
//             throw new Error("Network response was not ok " + response.statusText);
//         }
//         // Log the response text for debugging
//         return response.text().then(text => {
//             console.log("Response received: ", text);  // Log the raw response
//             return JSON.parse(text);  // Try to parse it as JSON
//         });
//     })
//     .then(data => {
//         if (data.length > 0) {
//             displayMatchingRecipes(data);  // Display matching recipes if found
//         } else {
//             alert("No recipes found for the given ingredients.");
//             fetchLatestRecipes();  // If no recipes found, show the latest approved recipes
//         }
//     })
//     .catch(error => {
//         console.error('Error fetching recipes:', error);
//         alert("Something went wrong. Please try again later.");
//     });
// }


// Function to replace the "Latest Approved Recipes" section with matching recipes
function displayMatchingRecipes(recipes) {
    
    // Update the section titles
    const sectionTitle = document.getElementById('section-title');
    sectionTitle.innerHTML = 'Matching Recipes';

    const exactMatches = recipes[0] || [];  // First array: Exact Matches
    const suggestedRecipes = recipes[1] || [];  // Second array: Suggested Recipes

    
    const matchingRecipesContainer = document.getElementById('matching-recipes-list');
    // Display exact matches or show "No matching recipes"
    if (exactMatches.length > 0) {
        // Get the Matching Recipes container
        matchingRecipesContainer.innerHTML = '';  // Clear previous content
        displayRecipes(exactMatches, matchingRecipesContainer);  // Display exact matches
    } else {
        matchingRecipesContainer.innerHTML = '<p>No matching recipes for your given ingredients.</p>';
    }
    
    const suggestedRecipesTitle = document.getElementById('suggested-section-title');
    suggestedRecipesTitle.innerHTML = 'Suggested Recipes';
    const suggestedRecipesContainer = document.getElementById('suggested-recipes-list');
    // Display suggested recipes
    if (suggestedRecipes.length > 0) {
        suggestedRecipesContainer.innerHTML = '';  // Clear previous content
        displayRecipes(suggestedRecipes, suggestedRecipesContainer);  // Display suggested recipes
    } else {
        suggestedRecipesContainer.innerHTML = '<p>No suggested recipes available.</p>';
    }
}


// Array to track selected ingredients
let selectedIngredients = [];


// Function to fetch and display ingredient suggestions as the user types
function fetchIngredientSuggestions(query) {
    if (query.trim() === "") {
        document.getElementById('suggestions-box').classList.add('hidden');
        return;
    }

    // Send an AJAX request to fetch ingredients suggestions from the database
    fetch('fetch_ingredients_suggestions.php?search=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(suggestions => {
            // Filter out ingredients already selected
            const filteredSuggestions = suggestions.filter(suggestion => !selectedIngredients.includes(suggestion));
            displayIngredientSuggestions(filteredSuggestions); // Display filtered suggestions
        })
        .catch(error => {
            console.error('Error fetching ingredient suggestions:', error);
        });
}


// Function to display ingredient suggestions below the input field
function displayIngredientSuggestions(suggestions) {
    const suggestionBox = document.getElementById('suggestions-box');
    suggestionBox.innerHTML = '';  // Clear previous suggestions

    if (suggestions.length > 0) {
        suggestionBox.classList.remove('hidden');  // Show suggestions box
        suggestions.forEach(suggestion => {
            const suggestionItem = document.createElement('div');
            suggestionItem.classList.add('suggestion-item', 'p-2', 'cursor-pointer', 'hover:bg-gray-200');
            suggestionItem.textContent = suggestion;
            suggestionItem.onclick = () => {
                addIngredientToSelected(suggestion);  // Add suggestion to selected ingredients
                suggestionBox.classList.add('hidden');  // Hide suggestions after selection
                
                // Clear the input field
                document.getElementById('ingredients-input').value = '';  // Clear the input field
            };
            suggestionBox.appendChild(suggestionItem);
        });
    } else {
        suggestionBox.classList.add('hidden');  // Hide suggestions box if no suggestions
    }
}


// Function to add an ingredient tag to the "Your Ingredients" section
function addIngredientToSelected(ingredient) {
    const selectedIngredientsDiv = document.getElementById('selected-ingredients');
    const selectedIngredientsSection = document.getElementById('selected-ingredients-section');
    
    // Add ingredient to the selected ingredients array
    selectedIngredients.push(ingredient);

    // Create a new tag (chip) for the selected ingredient
    const ingredientTag = document.createElement('div');
    ingredientTag.classList.add('ingredient-chip','bg-gray-200', 'rounded-full', 'px-3', 'py-1', 'flex', 'items-center', 'justify-between');
    ingredientTag.textContent = ingredient;

    // Create the remove button for the tag
    const removeBtn = document.createElement('span');
    removeBtn.textContent = '×';
    removeBtn.classList.add('remove-btn');
    removeBtn.onclick = () => {
        ingredientTag.remove();  // Remove the ingredient chip
        selectedIngredients = selectedIngredients.filter(item => item !== ingredient);  // Remove ingredient from the array
        checkIfIngredientsAreEmpty(); // Check if the section should be hidden
    };

    // Append the remove button and tag to the container
    ingredientTag.appendChild(removeBtn);
    selectedIngredientsDiv.appendChild(ingredientTag);

    // Show the "Your Ingredients" section if an ingredient is added
    selectedIngredientsSection.classList.remove('hidden');
}


// Function to check if the "Your Ingredients" section should be hidden
function checkIfIngredientsAreEmpty() {
    const selectedIngredientsDiv = document.getElementById('selected-ingredients');
    const selectedIngredientsSection = document.getElementById('selected-ingredients-section');

    // If there are no ingredients left, hide the "Your Ingredients" section
    if (selectedIngredientsDiv.children.length === 0) {
        selectedIngredientsSection.classList.add('hidden');
    }
}


// Function to trigger the search based on selected ingredients
function searchRecipes() {
    const selectedIngredientsDiv = document.getElementById('selected-ingredients');
    const ingredientsArray = [];

    // Get all ingredient names from the selected chips
    Array.from(selectedIngredientsDiv.children).forEach(tag => {
        ingredientsArray.push(tag.textContent.replace('×', '').trim());  // Remove the '×' (remove button)
    });

    // Prepare the data to be sent in the POST request
    const requestData = {
        ingredients: ingredientsArray.join(', ')  // Send the selected ingredients as a string
    };

    // Fetch the matching recipes based on the selected ingredients
    fetch('fetch_recipes_by_ingredients.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)  // Send the selected ingredients to PHP as JSON
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok " + response.statusText);
        }
        return response.json();  // Parse the response as JSON
    })
   .then(data => {
    // Ensure exactMatches and suggestedMatches are arrays (even if empty)
    const exactMatches = Array.isArray(data.exactMatches) ? data.exactMatches : [];
    const suggestedMatches = Array.isArray(data.suggestedMatches) ? data.suggestedMatches : [];

    // Check if the response contains both 'exactMatches' and 'suggestedMatches'
    if (exactMatches.length > 0 || suggestedMatches.length > 0) {
        displayMatchingRecipes([exactMatches, suggestedMatches]);  // Pass both arrays as a nested array
    } else {
        alert("No recipes found for the selected ingredients.");
        document.getElementById('selected-ingredients-section').classList.add('hidden');  // Hide section if no results
    }

        // After the search is completed, clear the previous chips (selected ingredients)
        selectedIngredientsDiv.innerHTML = '';  // Clear the ingredient chips
        selectedIngredients = [];  // Clear the selectedIngredients array
        document.getElementById('selected-ingredients-section').classList.add('hidden');
    })
    .catch(error => {
        console.error('Error fetching recipes:', error);
        alert("Something went wrong. Please try again later.");
        document.getElementById('selected-ingredients-section').classList.add('hidden');  // Hide section on error
    });
}



// Attach the fetchIngredientSuggestions to the ingredients input field
document.getElementById('ingredients-input').addEventListener('input', function() {
    fetchIngredientSuggestions(this.value);
});


// When the page loads, display the latest recipes
document.addEventListener('DOMContentLoaded', fetchLatestRecipes);
