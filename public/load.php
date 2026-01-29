<?php
require_once "../config/db.php";
require_once '../includes/header.php';
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 3;

$stmt = $pdo->prepare("
    SELECT posts.id, posts.title, posts.created_at, users.username AS author
    FROM posts
    JOIN users ON posts.author_id = users.id
    ORDER BY posts.created_at DESC
    LIMIT $limit OFFSET $offset
");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    echo "<div class='post'>
            <h2>
                <a href='post.php?id={$post['id']}'>" .
                htmlspecialchars($post['title']) .
            "</a></h2>
            <p class='meta'>By " .
            htmlspecialchars($post['author']) .
            "</p>
          </div>";
}
