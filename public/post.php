<?php
require_once '../config/db.php';
require_once '../includes/functions.php';



if (!isset($_GET['id'])) {
    die("Post not found");
}

$post_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT posts.*, users.username AS author
    FROM posts
    JOIN users ON posts.author_id = users.id
    WHERE posts.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

$commentsStmt = $pdo->prepare(
    "SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC"
);
$commentsStmt->execute([$post_id]);
$comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>

<h2><?= htmlspecialchars($post['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

<hr>

<h3>Comments</h3>

<?php foreach ($comments as $comment): ?>
    <div style="border:1px solid #ccc; margin-bottom:10px; padding:10px;">
        <b><?= htmlspecialchars($comment['name']) ?></b><br>
        <?= nl2br(htmlspecialchars($comment['comment'])) ?><br>
        <small><?= $comment['created_at'] ?></small>
    </div>
<?php endforeach; ?>

<h3>Add Comment</h3>

<form method="post" action="add_comment.php">
    <input type="hidden" name="csrf_token" value="<?= generateToken(); ?>">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">

    <input type="text" name="name" placeholder="Your name" required><br><br>
    <textarea name="comment" required></textarea><br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>
