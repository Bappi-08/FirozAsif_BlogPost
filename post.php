<?php
include 'includes/connection.php';
include 'includes/nav.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;
    $writer = $_POST['writer'] ?? null;
    $photo = $_FILES['photo']['name'] ?? null;

    // Validation
    if (!$title || strlen($title) > 50) {
        echo "Title is required and should not exceed 50 characters.";
    } elseif (!$content) {
        echo "Content is required.";
    } elseif (!$writer) {
        echo "Writer's name is required.";
    } elseif (!$photo) {
        echo "A photo is required.";
    } else {
        if (isset($_POST['id']) && $_POST['id']) {
            // Update post
            $id = $_POST['id'];
            if ($photo) {
                move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/$photo");
                $query = $conn->prepare("UPDATE blogs SET title=?, content=?, writer=?, photo=? WHERE id=?");
                $query->execute([$title, $content, $writer, $photo, $id]);
            } else {
                $query = $conn->prepare("UPDATE blogs SET title=?, content=?, writer=? WHERE id=?");
                $query->execute([$title, $content, $writer, $id]);
            }
        } else {
            // Insert post
            move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/$photo");
            $query = $conn->prepare("INSERT INTO blogs (title, content, writer, photo) VALUES (?, ?, ?, ?)");
            $query->execute([$title, $content, $writer, $photo]);
        }
        header('Location: index.php');
        exit;
    }
}

// Delete post
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = $conn->prepare("DELETE FROM blogs WHERE id=?");
    $query->execute([$id]);
    header('Location: index.php');
    exit;
}

// Fetch post for editing
$post = ['title' => '', 'content' => '', 'writer' => '', 'photo' => ''];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = $conn->prepare("SELECT * FROM blogs WHERE id=?");
    $query->execute([$id]);
    $post = $query->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Post</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        form input[type="text"],
        form textarea,
        form input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        form textarea {
            height: 150px;
            resize: none;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }

        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<h1><?php echo isset($_GET['edit']) ? 'Edit' : 'Create'; ?> Post</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $post['id'] ?? ''; ?>">
    <label>Title:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
    <label>Content:</label>
    <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
    <label>Writer:</label>
    <input type="text" name="writer" value="<?php echo htmlspecialchars($post['writer']); ?>" required>
    <label>Photo:</label>
    <input type="file" name="photo" <?php echo isset($_GET['edit']) ? '' : 'required'; ?>>
    <button type="submit"><?php echo isset($_GET['edit']) ? 'Update' : 'Create'; ?></button>
</form>
</body>
</html>
