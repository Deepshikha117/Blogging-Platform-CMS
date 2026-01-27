<?php
require_once '../includes/auth.php';
require_once '../config/db.php';


// Fetch all posts with category & author
$sql = "
    SELECT posts.id, posts.title, posts.created_at,
           categories.name AS category,
           users.username AS author
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    JOIN users ON posts.author_id = users.id
    ORDER BY posts.created_at DESC
";

$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Posts</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { width: 90%; margin: 40px auto; background: #fff; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #333; color: #fff; }
        a { text-decoration: none; padding: 6px 10px; }
        .edit { background: #4CAF50; color: white; }
        .delete { background: #f44336; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Posts</h2>

    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= htmlspecialchars($post['title']) ?></td>
            <td><?= htmlspecialchars($post['category']) ?></td>
            <td><?= htmlspecialchars($post['author']) ?></td>
            <td><?= $post['created_at'] ?></td>
            <td>
                <a class="edit" href="edit.php?id=<?= $post['id'] ?>">Edit</a>
                <a class="delete" href="delete.php?id=<?= $post['id'] ?>"
                   onclick="return confirm('Delete this post?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
