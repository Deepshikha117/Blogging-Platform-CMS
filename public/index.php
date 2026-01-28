<?php
require_once "../config/db.php";

// Initial load
$stmt = $pdo->prepare("
    SELECT posts.id, posts.title, posts.created_at, users.username AS author
    FROM posts
    JOIN users ON posts.author_id = users.id
    ORDER BY posts.created_at DESC
    LIMIT 3 OFFSET 0
");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blogging Platform CMS</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; }
        .container { width: 800px; margin: 30px auto; }
        .post { background: #fff; padding: 15px; margin-bottom: 15px; }
        .meta { font-size: 13px; color: #777; }
        button { padding: 10px 15px; }
    </style>
</head>
<body>

<div class="container">
    <h1>📚 Blogging Platform CMS</h1>
    <p>Public Read-Only Blog</p>

    <form action="search.php" method="get">
        <input type="text" name="q" placeholder="Search posts or author">
        <button type="submit">Search</button>
    </form>

    <hr>

    <div id="posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2>
                    <a href="post.php?id=<?= $post['id'] ?>">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h2>
                <p class="meta">
                    By <?= htmlspecialchars($post['author']) ?> |
                    <?= date("F d, Y", strtotime($post['created_at'])) ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

    <button id="loadMore">Load More</button>
</div>

<script>
let offset = 3;

document.getElementById("loadMore").onclick = function () {
    fetch("load_posts.php?offset=" + offset)
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "") {
                document.getElementById("loadMore").innerText = "No more posts";
                document.getElementById("loadMore").disabled = true;
            } else {
                document.getElementById("posts")
                    .insertAdjacentHTML("beforeend", data);
                offset += 3;
            }
        });
};
</script>

</body>
</html>
