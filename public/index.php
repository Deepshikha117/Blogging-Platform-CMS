<?php
require_once '../config/db.php';



// Load first 5 posts
$stmt = $pdo->prepare("
    SELECT posts.id, posts.title, posts.content, posts.created_at,
           users.username AS author
    FROM posts
    JOIN users ON posts.author_id = users.id
    ORDER BY posts.created_at DESC
    LIMIT 5 OFFSET 0
");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog Home</title>
    <script>
        let offset = 5;

        function loadMorePosts() {
            fetch("load.php?offset=" + offset)
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "") {
                        document.getElementById("loadBtn").style.display = "none";
                    } else {
                        document.getElementById("posts").innerHTML += data;
                        offset += 5;
                    }
                });
        }
    </script>
</head>
<body>

<h2>Latest Blog Posts</h2>

<div id="posts">
    <?php foreach ($posts as $post): ?>
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
</div>

<button id="loadBtn" onclick="loadMorePosts()">Load More</button>

</body>
</html>
