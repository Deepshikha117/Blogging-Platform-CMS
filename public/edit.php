<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: manage.php");
    exit;
}

$post_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found");
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifyToken($_POST['csrf_token'] ?? '')) {
        die("CSRF validation failed");
    }

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int)$_POST['category'];

    if ($title && $content && $category_id) {
        $stmt = $pdo->prepare(
            "UPDATE posts
             SET title = ?, content = ?, category_id = ?, updated_at = NOW()
             WHERE id = ?"
        );
        $stmt->execute([$title, $content, $category_id, $post_id]);
        $message = "Post updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
</head>
<body>
<div class="container">
<h2>Edit Post</h2>



<?php if ($message): ?>
    <p style="color:green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= generateToken(); ?>">

    <input type="text" name="title"
           value="<?= htmlspecialchars($post['title']) ?>" required><br><br>

    <textarea name="content" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>

    <select name="category" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= $cat['id'] == $post['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Update</button>
</form>
</div>
</body>
</html>

<?php require_once '../includes/footer.php'; ?>