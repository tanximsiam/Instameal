<?php
// Include the database connection
require_once 'db.connect.php';

// Get the search term from the GET request (trim any spaces)
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// If the search term is empty, return an empty array
if (empty($searchTerm)) {
    echo json_encode([]);
    exit;
}

// Log the search term (useful for debugging)
error_log("Search term: " . $searchTerm);

// SQL query to fetch ingredients matching the search term (case-insensitive)
$sql = "
    SELECT DISTINCT name 
    FROM ingredients 
    WHERE LOWER(name) LIKE LOWER(?) 
    LIMIT 10";  // Limit the results to avoid overloading the client with too many suggestions

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$searchTerm = "%$searchTerm%";  // Add wildcards for partial matching
$stmt->bind_param('s', $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results into an array
$ingredients = [];
while ($row = $result->fetch_assoc()) {
    $ingredients[] = $row['name'];
}

// Log the suggestions (useful for debugging)
error_log("Suggestions: " . implode(", ", $ingredients));

// Return the results as a JSON array
echo json_encode($ingredients);

// Close the connection
$stmt->close();
$conn->close();
?>