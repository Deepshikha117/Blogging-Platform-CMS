<?php
session_start();
require_once __DIR__ . '/../config/db.php';

/* ---------------- SECURITY CHECK ---------------- */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* ---------------- CSRF TOKEN ---------------- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* ---------------- FETCH CATEGORIES ---------------- */
$catStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------------- FETCH TAGS ---------------- */
$tagStmt = $pdo->query("SELECT id, name FROM tags ORDER BY name");
$tags = $tagStmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------------- FORM SUBMISSION ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* CSRF VALIDATION */
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    /* INPUT SANITIZATION */
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int) $_POST['category_id'];
    $author_id = $_SESSION['user_id'];

    if ($title === '' || $content === '' || $category_id === 0) {
        $error = "All fields are required.";
    } else {

        /* INSERT POST */
        $postStmt = $pdo->prepare("
            INSERT INTO posts (title, content, author_id, category_id, created_at)
            VALUES (:title, :content, :author_id, :category_id, NOW())
        ");

        $postStmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':author_id' => $author_id,
            ':category_id' => $category_id
        ]);

        $post_id = $pdo->lastInsertId();

        /* INSERT TAGS (many-to-many) */
        if (!empty($_POST['tags'])) {
            $tagInsert = $pdo->prepare("
                INSERT INTO post_tags (post_id, tag_id)
                VALUES (:post_id, :tag_id)
            ");

            foreach ($_POST['tags'] as $tag_id) {
                $tagInsert->execute([
                    ':post_id' => $post_id,
                    ':tag_id' => (int)$tag_id
                ]);
            }
        }

        header("Location: index.php?success=post_added");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Post</title>
</head>
<body>

<h2>Add New Post</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST">

    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <label>Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Content</label><br>
    <textarea name="content" rows="6" required></textarea><br><br>

    <label>Category</label><br>
    <select name="category_id" required>
        <option value="">Select category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Tags</label><br>
    <?php foreach ($tags as $tag): ?>
        <label>
            <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>">
            <?php echo htmlspecialchars($tag['name']); ?>
        </label><br>
    <?php endforeach; ?>

    <br>
    <button type="submit">Publish</button>
</form>

</body>
</html>
