<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/functions.php';



$message = '';

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifyToken($_POST['csrf_token'] ?? '')) {
        die("CSRF validation failed");
    }

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int)$_POST['category'];

    if ($title && $content && $category_id) {
        $stmt = $pdo->prepare(
            "INSERT INTO posts (title, content, author_id, category_id, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $title,
            $content,
            $_SESSION['user_id'],
            $category_id
        ]);

        $message = "Post added successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Post</title>
</head>
<body>

<h2>Add New Post</h2>

<?php if ($message): ?>
    <p style="color:green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= generateToken(); ?>">

    <input type="text" name="title" placeholder="Post title" required><br><br>

    <textarea name="content" placeholder="Post content" rows="6" required></textarea><br><br>

    <select name="category" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Publish</button>
</form>

</body>
</html>
