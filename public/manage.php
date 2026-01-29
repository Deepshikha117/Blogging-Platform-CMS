<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/header.php';

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
        body { font-family: Oswald; background: #ffffff; }
        .container { width: 90%; margin: 40px auto; background: #CBDCB8; padding: 20px; border-radius: 30px;}
        table { width: 100%; border-collapse: collapse; color:black; }
        th, td { padding: 10px; border-bottom: 1px solid #ffffff; text-align: left; }
        th { background: #485935; color: #F4F3F1 }
        a { text-decoration: none; padding: 6px 10px; border-radius: 10px;}
        .edit { background: #E9B957; color: #F4F3F1; }
        .delete { background: RED; color: #F4F3F1; }
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
<?php require_once '../includes/footer.php'; ?>