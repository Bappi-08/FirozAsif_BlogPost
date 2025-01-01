<?php
include '../includes/connection.php';
include '../includes/functions.php';

// Check database connection
if (!$conn) {
    die(json_encode([
        'status' => 500,
        'message' => 'Database connection failed.'
    ]));
}

// Set headers for API response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization');

// Handle request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    // Fetch blog list
    $blogList = getBlogList();
    echo $blogList;
} elseif ($requestMethod == "POST") {
    // Write new blog post
    $inputData = json_decode(file_get_contents("php://input"), true);
    $response = createBlogPost($inputData);
    echo $response;
} else {
    // Respond with 405 Method Not Allowed for unsupported methods
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
?>
