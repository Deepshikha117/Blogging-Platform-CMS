<?php
require_once "../config/db.php";
require_once '../includes/header.php';
$q = $_GET['q'] ?? '';
$results = [];

if ($q) {
    $search = "%$q%";
    $stmt = $pdo->prepare("
        SELECT posts.id, posts.title, posts.created_at, users.username AS author
        FROM posts
        JOIN users ON posts.author_id = users.id
        WHERE posts.title LIKE ?
           OR posts.content LIKE ?
           OR users.username LIKE ?
        ORDER BY posts.created_at DESC
    ");
    $stmt->execute([$search, $search, $search]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
</head>
<body>

<h1>🔍 Search Results</h1>

<form method="get">
    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>">
    <button type="submit">Search</button>
</form>

<hr>

<?php if ($q && empty($results)): ?>
    <p>No results found.</p>
<?php endif; ?>

<?php foreach ($results as $post): ?>
    <h3>
        <a href="post.php?id=<?= $post['id'] ?>">
            <?= htmlspecialchars($post['title']) ?>
        </a>
    </h3>
    <small>
        By <?= htmlspecialchars($post['author']) ?> |
        <?= date("F d, Y", strtotime($post['created_at'])) ?>
    </small>
    <hr>
<?php endforeach; ?>

<a href="index.php">← Back to blog</a>

</body>
</html>
<?php require_once '../includes/footer.php'; ?>