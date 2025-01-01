<?php
include_once "connection.php";

function getBlogList() {
    global $conn; // PDO instance
    $query = "SELECT * FROM blogs";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($res) {
            $data = [
                'status' => 200,
                'message' => 'Blog List successfully fetched',
                'data' => $res
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No blog post found'
            ];
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    } catch (PDOException $e) {
        $data = [
            'status' => 500,
            'message' => 'Internal server error: ' . $e->getMessage()
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function createBlogPost($data) {
    global $conn;

    // Validate input data
    if (!isset($data['title']) || !isset($data['content']) || !isset($data['writer'])) {
        $response = [
            'status' => 400,
            'message' => 'Invalid input. Title, content, and writer are required.'
        ];
        header("HTTP/1.0 400 Bad Request");
        return json_encode($response);
    }

    try {
        $query = "INSERT INTO blogs (title, content, writer) VALUES (:title, :content, :writer)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':writer', $data['writer']);
        $stmt->execute();

        $response = [
            'status' => 201,
            'message' => 'Blog post created successfully',
            'data' => [
                'id' => $conn->lastInsertId(),
                'title' => $data['title'],
                'content' => $data['content'],
                'writer' => $data['writer']
            ]
        ];
        header("HTTP/1.0 201 Created");
        return json_encode($response);
    } catch (PDOException $e) {
        $response = [
            'status' => 500,
            'message' => 'Internal server error: ' . $e->getMessage()
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($response);
    }
}
?>
