<?php
require_once "../config/db.php";

/* -------------------------
   1. Validate Post ID
-------------------------- */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Post not found");
}

$post_id = (int) $_GET['id'];

/* -------------------------
   2. Fetch Post + Author + Category
-------------------------- */
$stmt = $pdo->prepare("
    SELECT 
        posts.id,
        posts.title,
        posts.content,
        posts.created_at,
        posts.category_id,
        users.username AS author,
        categories.name AS category
    FROM posts
    JOIN users ON posts.author_id = users.id
    LEFT JOIN categories ON posts.category_id = categories.id
    WHERE posts.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found");
}

/* -------------------------
   3. Handle Comment Submission
-------------------------- */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $comment = trim($_POST["comment"]);

    if ($name && $comment) {
        $stmt = $pdo->prepare(
            "INSERT INTO comments (post_id, name, comment)
             VALUES (?, ?, ?)"
        );
        $stmt->execute([$post_id, $name, $comment]);
    }
}

/* -------------------------
   4. Fetch Comments
-------------------------- */
$stmt = $pdo->prepare("
    SELECT name, comment, created_at
    FROM comments
    WHERE post_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* -------------------------
   5. Fetch Tags
-------------------------- */
$stmt = $pdo->prepare("
    SELECT tags.name
    FROM tags
    JOIN post_tags ON tags.id = post_tags.tag_id
    WHERE post_tags.post_id = ?
");
$stmt->execute([$post_id]);
$tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .container {
            width: 800px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
        }
        .meta {
            color: #777;
            font-size: 14px;
        }
        .tags span {
            background: #eee;
            padding: 4px 8px;
            margin-right: 5px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">

    <a href="index.php">← Back to Blog</a>

    <h1><?= htmlspecialchars($post['title']) ?></h1>

    <p class="meta">
        By <?= htmlspecialchars($post['author']) ?> |
        <?= date("F d, Y", strtotime($post['created_at'])) ?>
    </p>

    <p><strong>Category:</strong>
        <?= htmlspecialchars($post['category'] ?? 'Uncategorized') ?>
    </p>

    <?php if (!empty($tags)): ?>
        <p class="tags">
            <strong>Tags:</strong>
            <?php foreach ($tags as $tag): ?>
                <span><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
        </p>
    <?php endif; ?>

    <hr>

    <p>
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </p>

    <hr>

    <h3>💬 Comments</h3>

    <form method="post">
        <input type="text" name="name" placeholder="Your name" required><br><br>
        <textarea name="comment" placeholder="Your comment" required></textarea><br><br>
        <button type="submit">Post Comment</button>
    </form>

    <hr>

    <?php if (empty($comments)): ?>
        <p>No comments yet.</p>
    <?php endif; ?>

    <?php foreach ($comments as $c): ?>
        <p>
            <strong><?= htmlspecialchars($c['name']) ?></strong><br>
            <?= nl2br(htmlspecialchars($c['comment'])) ?><br>
            <small><?= $c['created_at'] ?></small>
        </p>
        <hr>
    <?php endforeach; ?>

</div>

</body>
</html>
