<?php
$host = 'db'; // Use the service name in docker-compose.yml
$user = 'user';
$password = 'userpassword';
$database = 'blog_project';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>
