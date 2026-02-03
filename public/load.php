<?php
require_once "../config/db.php";

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

$sql = "
    SELECT posts.id, posts.title, posts.created_at, users.username AS author
    FROM posts
    JOIN users ON posts.author_id = users.id
    WHERE 1
";

$params = [];

/* Apply same filters */
if (!empty($_GET['keyword'])) {
    $sql .= " AND posts.title LIKE :keyword";
    $params[':keyword'] = '%' . $_GET['keyword'] . '%';
}

if (!empty($_GET['author'])) {
    $sql .= " AND users.id = :author";
    $params[':author'] = $_GET['author'];
}

$sql .= " ORDER BY posts.created_at DESC LIMIT 3 OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Output SAME card HTML */
foreach ($posts as $post): ?>
    <article class="post-card">
        <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
        <div class="post-meta">
            By <?= htmlspecialchars($post['author']) ?> •
            <?= date('M d, Y', strtotime($post['created_at'])) ?>
        </div>
        <a class="read-more" href="post.php?id=<?= $post['id'] ?>">
            Continue reading →
        </a>
    </article>
<?php endforeach; ?>
