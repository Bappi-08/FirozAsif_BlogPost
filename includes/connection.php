<?php
$host = 'db'; // Use the service name in docker-compose.yml
$user = 'firoz008';
$password = 'y_uZYhXE@_aut4n';
$database = 'blog_project';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>
