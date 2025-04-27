<?php
// Load environment variables from the .env file
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env');
    foreach ($lines as $line) {
        // Ignore comments and empty lines
        if (trim($line) === '' || strpos(trim($line), '#') === 0) {
            continue;
        }
        // Split the line into key and value
        list($key, $value) = explode('=', trim($line), 2);
        putenv("$key=$value");
    }
} else {
    die(".env file missing");
}

// Database configuration using array_map and unpacking
list($servername, $serverport, $username, $password, $dbname) = array_map('getenv', ['DB_HOST', 'DB_PORT', 'DB_USER', 'DB_PASSWORD', 'DB_NAME']);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>