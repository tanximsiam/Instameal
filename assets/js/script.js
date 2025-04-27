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
                displayRecipes(data);  // Call displayRecipes with the data
            } else {
                clearRecipes();  // Clear recipes if no data is found
            }
        })
        .catch(error => {
            console.error("Error fetching latest recipes:", error);
            alert("Something went wrong. Please try again later.");
        });
}


function displayRecipes(recipes) {
    const recipesList = document.getElementById('recipes-list');
    recipesList.innerHTML = '';  // Clear the list before appending new recipes

    // Set the parent container to grid layout for equal height cards, with gap and padding
    recipesList.classList.add(
        'flex', 'flex-wrap', 'grid', 'grid-cols-1', 'md:grid-cols-2',
        'gap-8', 'px-8', 'py-6', 'mx-auto', 'w-full', 'justify-items-center'
    );  // Tailwind CSS grid layout with responsive columns, gap, and padding

    recipes.forEach(recipe => {
        console.log("Displaying recipe:", recipe);  // Log each recipe being displayed
        
        // Create the recipe card container
        const recipeCard = document.createElement('div');
        
        // Add Tailwind CSS classes for styling and ensuring equal height cards
        recipeCard.classList.add(
            'bg-white', 'p-6', 'rounded-2xl', 'shadow', 
            'text-center', 'border', 'border-gray-300', 'flex', 'flex-col', 
            'h-full', 'w-full', 'm-2'
        );

        // Create the recipe name element
        const recipeName = document.createElement('h3');
        recipeName.textContent = recipe.name;  // Set the text of the recipe name
        recipeName.classList.add('text-xl', 'font-semibold', 'text-gray-800', 'mb-2');  // Styling for name

        // Create the recipe description element
        const recipeDescription = document.createElement('p');
        recipeDescription.textContent = recipe.description;  // Set the text of the recipe description
        recipeDescription.classList.add('text-gray-600', 'mb-2');  // Styling for description

        // Create the view recipe link
        const viewRecipeLink = document.createElement('a');
        viewRecipeLink.textContent = 'View Recipe';
        viewRecipeLink.href = `recipe_detail.php?id=${recipe.id}`;  // Set the link URL
        viewRecipeLink.classList.add('text-orange-500', 'hover:underline', 'mt-auto');  // Styling for link with margin to push to the bottom

        // Append all created elements to the recipe card
        recipeCard.appendChild(recipeName);
        recipeCard.appendChild(recipeDescription);
        recipeCard.appendChild(viewRecipeLink);

        // Append the recipe card to the main list of recipes
        recipesList.appendChild(recipeCard);
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
function searchRecipes() {
    const searchTerm = document.getElementById('ingredients-input').value.trim();

    if (searchTerm === "") {
        alert("Please enter ingredients to search!");
        return;
    }

    // Prepare the data to be sent in the POST request
    const requestData = {
        ingredients: searchTerm // Send the ingredients string entered by the user
    };

    // Fetch the matching recipes based on the ingredients
    fetch('fetch_recipes_by_ingredients.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)  // Send the search term to PHP as JSON
    })
    .then(response => {
        // Check if the response is OK (status 200)
        if (!response.ok) {
            throw new Error("Network response was not ok " + response.statusText);
        }
        // Log the response text for debugging
        return response.text().then(text => {
            console.log("Response received: ", text);  // Log the raw response
            return JSON.parse(text);  // Try to parse it as JSON
        });
    })
    .then(data => {
        if (data.length > 0) {
            displayMatchingRecipes(data);  // Display matching recipes if found
        } else {
            alert("No recipes found for the given ingredients.");
            displayLatestRecipes();  // If no recipes found, show the latest approved recipes
        }
    })
    .catch(error => {
        console.error('Error fetching recipes:', error);
        alert("Something went wrong. Please try again later.");
    });
}

// Function to replace the "Latest Approved Recipes" section with matching recipes
function displayMatchingRecipes(recipes) {
    const recipesSection = document.getElementById('recipes-list');
    recipesSection.innerHTML = '';  // Clear previous recipes

    const sectionTitle = document.getElementById('section-title');
    sectionTitle.innerHTML = 'Matching Recipes';

    // const sectionTitle = document.createElement('h2');
    // sectionTitle.textContent = "Matching Recipes";
    // sectionTitle.classList.add('text-2xl', 'font-semibold', 'text-center', 'mb-8');
    // recipesSection.appendChild(sectionTitle);

    // Loop through the recipes and display them
    recipes.forEach(recipe => {
        const recipeCard = document.createElement('div');
        recipeCard.classList.add(
            'bg-white', 'p-6', 'rounded-2xl', 'shadow', 
            'text-center', 'border', 'border-gray-300', 'flex', 'flex-col', 
            'h-full', 'w-full', 'm-2' // Ensure the card uses full width of the grid)
        );
        const recipeName = document.createElement('h3');
        recipeName.textContent = recipe.name;
        recipeName.classList.add('text-xl', 'font-semibold', 'text-gray-800', 'mb-2');

        const recipeDescription = document.createElement('p');
        recipeDescription.textContent = recipe.description;
        recipeDescription.classList.add('text-gray-600', 'mb-4');

        const viewRecipeLink = document.createElement('a');
        viewRecipeLink.textContent = 'View Recipe';
        viewRecipeLink.href = `recipe_detail.php?id=${recipe.id}`;
        viewRecipeLink.classList.add('text-orange-500', 'hover:underline', 'mt-auto');

        recipeCard.appendChild(recipeName);
        recipeCard.appendChild(recipeDescription);
        recipeCard.appendChild(viewRecipeLink);
        recipesSection.appendChild(recipeCard);
    });
}


// Function to display the latest recipes when no search results are found
function displayLatestRecipes() {
    // Call the function to fetch and display the latest approved recipes again
    fetchLatestRecipes();
}

// When the page loads, display the latest recipes
document.addEventListener('DOMContentLoaded', fetchLatestRecipes);
