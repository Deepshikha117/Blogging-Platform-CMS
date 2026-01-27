<?php
require_once '../config/db.php';



$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

$stmt = $pdo->prepare("
    SELECT posts.id, posts.title, posts.content, posts.created_at,
           users.username AS author
    FROM posts
    JOIN users ON posts.author_id = users.id
    ORDER BY posts.created_at DESC
    LIMIT 5 OFFSET ?
");

$stmt->bindValue(1, $offset, PDO::PARAM_INT);
$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3>
            <a href="post.php?id=<?= $post['id'] ?>">
                <?= htmlspecialchars($post['title']) ?>
            </a>
        </h3>
        <p><?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?>...</p>
        <small>By <?= htmlspecialchars($post['author']) ?> | <?= $post['created_at'] ?></small>
    </div>
<?php endforeach; ?>
