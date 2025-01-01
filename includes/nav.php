<?php
// Navigation Bar
echo '
<nav class="navbar">
    <a href="index.php" class="nav-link">Home</a>
    <a href="post.php" class="nav-link">Create Post</a>
</nav>
';
?>

<style>
    .navbar {
        background-color: #333;
        padding: 10px 20px;
        text-align: center;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        padding: 12px 20px;
        margin: 0 10px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .nav-link:hover {
        background-color: #007bff;
    }
</style>
