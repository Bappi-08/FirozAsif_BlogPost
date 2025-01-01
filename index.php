<?php
include 'includes/connection.php';


// Fetch posts
$query = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .top-bar a {
            text-decoration: none;
            padding: 10px 15px;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .top-bar a:hover {
            background-color: #0056b3;
        }
        .post {
            background: #fff;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .post img {
            width: 100%;
            max-width: 300px;
            height: auto;
            display: block;
            margin: 10px 0;
            border-radius: 5px;
        }
        .post h2 {
            margin: 0 0 10px;
            color: #555;
        }
        .post p {
            color: #666;
            line-height: 1.6;
        }
        .post a {
            display: inline-block;
            margin-right: 10px;
            padding: 8px 12px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .post a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Top Bar with Home and Create Post -->
        <div class="top-bar">
            <a href="index.php">Home</a>
            <a href="post.php">Create Post</a>
        </div>

        <h1>Blog Posts</h1>

        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p>
                    <img src="uploads/<?php echo $post['photo']; ?>" alt="Blog Image">
                </p>
                <p><?php echo htmlspecialchars($post['content']); ?></p>
                <p><b>Author:</b> <?php echo htmlspecialchars($post['writer']); ?></p>
                <!-- <a href="page.php?id=<?php echo $post['id']; ?>">Read More</a> -->
                <a href="post.php?edit=<?php echo $post['id']; ?>">Edit</a>
                <a href="post.php?delete=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
