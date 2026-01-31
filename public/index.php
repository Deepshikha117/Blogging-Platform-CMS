<?php
require_once "../config/db.php";
require_once '../includes/header.php';
// Initial load
$stmt = $pdo->prepare("
    SELECT posts.id, posts.title, posts.created_at, users.username AS author
    FROM posts
    JOIN users ON posts.author_id = users.id
    ORDER BY posts.created_at DESC
    LIMIT 6 OFFSET 0
");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- 🔔 FLASH MESSAGE GOES HERE -->
<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert success" role="alert">
        <?php
            echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Blogging Platform CMS</title>
   
</head>
<body>

<div class="container">
    <h1>Blogging Platform CMS</h1>
    <p>Public Read-Only Blog</p>

    <form action="search.php" method="get">
        <input type="text" name="q" placeholder="Search posts or author">
        <button type="submit">Search</button>
    </form>

    <hr>

<div class="container">

    <h2 class="page-title">Latest Posts</h2>

    <div class="post-grid">

        <?php foreach ($posts as $post): ?>
            <article class="post-card">

                <h3 class="post-title">
                    <?= htmlspecialchars($post['title']) ?>
                </h3>

               

                <a class="read-more" href="post.php?id=<?= $post['id'] ?>">
                    Read more →
                </a>

            </article>
        <?php endforeach; ?>

    </div>

</div>


<script>
let offset = 3;

document.getElementById("loadMore").onclick = function () {
    fetch("load.php?offset=" + offset)
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
<?php require_once '../includes/footer.php'; ?>
