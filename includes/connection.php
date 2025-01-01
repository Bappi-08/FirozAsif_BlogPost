<?php
// Database Connection File

// Fetch database connection details from environment variables
$host = getenv('DB_HOST') ?: 'localhost'; // Default to 'localhost' if not set
$db = getenv('DB_NAME') ?: 'blog_database'; // Default to 'blog_database' if not set
$user = getenv('DB_USER') ?: 'root'; // Default to 'root' if not set
$pass = getenv('DB_PASSWORD') ?: ''; // Default to empty if not set

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
