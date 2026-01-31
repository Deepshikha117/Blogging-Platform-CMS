<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

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
        $_SESSION['success_message'] = "Post published successfully!";
        header("Location: index.php");
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

<div class="container">

    <!-- MAIN EDITOR -->
    <main class="editor-main" role="main">
        <h2>Add New Post</h2>
        

        <?php if (!empty($error)): ?>
            <div class="alert error" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="title">Post Title</label>
            <input type="text" id="title" name="title"
                   placeholder="Enter post title"
                   aria-required="true"
                   required>

            <label for="content">Content</label>
            <textarea id="content" name="content"
                      placeholder="Start writing your content..."
                      aria-required="true"
                      rows="14"
                      required></textarea>

    </main>

    <!-- SIDEBAR -->
    <aside class="editor-sidebar" role="complementary">

        <!-- CATEGORY -->
        <section class="editor-box">
            <h3>Category</h3>

            <select name="category_id" aria-required="true" required>
                <option value="">Select category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id']; ?>">
                        <?= htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </section>

        <!-- TAGS -->
        <section class="editor-box">
            <h3>Tags</h3>

            <div class="tag-list">
                <?php foreach ($tags as $tag): ?>
                    <label class="tag-item">
                        <input type="checkbox" name="tags[]" value="<?= $tag['id']; ?>">
                        <span><?= htmlspecialchars($tag['name']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- PUBLISH -->
        <section class="publish-box">
            <button type="submit" class="btn btn-success">
                Publish Post
            </button>
        </section>

    </aside>

    </form>
</div>

</body>
</html>
<?php require_once '../includes/footer.php'; ?>